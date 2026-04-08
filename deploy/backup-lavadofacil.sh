#!/usr/bin/env bash
# Backup diario de LavadoFácil: BD central + todas las BDs de tenants.
# Instalación en el VPS:
#   sudo cp /var/www/lavadofacil/deploy/backup-lavadofacil.sh /usr/local/bin/
#   sudo chmod +x /usr/local/bin/backup-lavadofacil.sh
#   sudo crontab -e
#   # Agregar:  0 3 * * * /usr/local/bin/backup-lavadofacil.sh

set -euo pipefail

APP_DIR="/var/www/lavadofacil"
BACKUP_DIR="/var/backups/lavadofacil"
RETENTION_DAYS=7
DATE=$(date +%Y-%m-%d)

# Leer credenciales del .env del proyecto (no hardcoded)
DB_USER=$(grep -E "^DB_USERNAME=" "$APP_DIR/.env" | cut -d'=' -f2- | tr -d '"')
DB_PASS=$(grep -E "^DB_PASSWORD=" "$APP_DIR/.env" | cut -d'=' -f2- | tr -d '"')
CENTRAL_DB=$(grep -E "^DB_DATABASE=" "$APP_DIR/.env" | cut -d'=' -f2- | tr -d '"')

mkdir -p "$BACKUP_DIR"

# 1) BD central
mysqldump -u "$DB_USER" -p"$DB_PASS" --single-transaction --quick --no-tablespaces \
    "$CENTRAL_DB" 2>/dev/null | gzip > "$BACKUP_DIR/${CENTRAL_DB}_${DATE}.sql.gz"

# 2) Todas las BDs de tenants (prefijo tenant_)
TENANT_DBS=$(mysql -u "$DB_USER" -p"$DB_PASS" -N -e "SHOW DATABASES LIKE 'tenant_%';" 2>/dev/null)
for db in $TENANT_DBS; do
    mysqldump -u "$DB_USER" -p"$DB_PASS" --single-transaction --quick --no-tablespaces \
        "$db" 2>/dev/null | gzip > "$BACKUP_DIR/${db}_${DATE}.sql.gz"
done

# 3) Storage del tenant (uploads de logos, etc.)
if [ -d /var/www/lavadofacil/storage/app ]; then
    tar -czf "$BACKUP_DIR/storage_${DATE}.tar.gz" -C /var/www/lavadofacil storage/app 2>/dev/null
fi

# 4) Rotación: borrar backups con más de N días
find "$BACKUP_DIR" -type f -name "*.gz" -mtime +${RETENTION_DAYS} -delete

echo "[$(date)] Backup OK -> $BACKUP_DIR"
