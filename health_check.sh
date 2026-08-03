#!/bin/bash
# ─────────────────────────────────────────────────────────────────────────────
# health_check.sh — Script de Diagnóstico, Respaldo DB y Envío de Correo (Ubuntu)
# Uso: ./health_check.sh
# ─────────────────────────────────────────────────────────────────────────────

# Colores para consola
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

echo -e "${BLUE}========================================================================${NC}"
echo -e "${BLUE}   🏥  SIPLAN — RESPALDO DE BASE DE DATOS Y REPORTE AL CORREO ${NC}"
echo -e "${BLUE}========================================================================${NC}"

# Ejecutar el comando Artisan nativo que realiza el dump pg_dump y envía el correo a jucfra23@gmail.com
if [ -f "$APP_DIR/artisan" ]; then
    cd "$APP_DIR" && php artisan siplan:health-and-backup "$@"
else
    echo -e "${RED}[❌] No se encontró el archivo artisan en $APP_DIR${NC}"
    exit 1
fi
