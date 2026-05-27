#!/bin/bash
# ─────────────────────────────────────────────────────────────────────────────
# deploy.sh — Script de despliegue para SIPLAN en Ubuntu + Nginx + PostgreSQL
# Uso: bash deploy.sh
# ─────────────────────────────────────────────────────────────────────────────

set -e  # Detener si hay error

APP_DIR="/var/www/siplan"   # ← Ajustá esta ruta a donde está tu app en el servidor
PHP="php8.2"                # ← Ajustá la versión de PHP que usás

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "  SIPLAN — Despliegue $(date '+%Y-%m-%d %H:%M:%S')"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

cd $APP_DIR

# 1. Modo mantenimiento
echo "▶ [1/8] Activando modo mantenimiento..."
$PHP artisan down --render="errors::503" --retry=60

# 2. Pull del código
echo "▶ [2/8] Actualizando código desde GitHub..."
git pull origin master

# 3. Dependencias PHP (sin dev en producción)
echo "▶ [3/8] Actualizando dependencias Composer..."
composer install --no-dev --optimize-autoloader --no-interaction

# 4. Migraciones — SOLO las nuevas, nunca fresh
echo "▶ [4/8] Ejecutando migraciones pendientes..."
$PHP artisan migrate --force

# 5. Seeders RIISS — solo si las tablas están vacías
echo "▶ [5/8] Verificando seeders RIISS..."

ESTABLECIMIENTOS=$($PHP artisan tinker --execute="echo \App\Models\Riiss\Establecimiento::count();" 2>/dev/null | grep -E '^[0-9]+$' | tail -1)
if [ "$ESTABLECIMIENTOS" = "0" ] || [ -z "$ESTABLECIMIENTOS" ]; then
    echo "   → Cargando establecimientos..."
    $PHP artisan db:seed --class=RiissEstablecimientoSeeder --force
    echo "   → Cargando cartera de servicios..."
    $PHP artisan db:seed --class=RiissCarteraServicioSeeder --force
    echo "   → Cargando formulario..."
    $PHP artisan db:seed --class=RiissFormularioSeeder --force
    echo "   → Cargando reglas de secciones..."
    $PHP artisan db:seed --class=RiissReglaSeccionSeeder --force
else
    echo "   → Establecimientos ya cargados ($ESTABLECIMIENTOS registros), saltando seeders RIISS"
fi

# 6. Limpiar y optimizar cachés
echo "▶ [6/8] Optimizando cachés..."
$PHP artisan config:cache
$PHP artisan route:cache
$PHP artisan view:cache
$PHP artisan event:cache

# 7. Permisos de storage
echo "▶ [7/8] Ajustando permisos..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# 8. Salir del modo mantenimiento
echo "▶ [8/8] Desactivando modo mantenimiento..."
$PHP artisan up

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "  ✅ Despliegue completado exitosamente"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
