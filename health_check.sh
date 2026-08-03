#!/bin/bash
# ─────────────────────────────────────────────────────────────────────────────
# health_check.sh — Diagnostic & Health Check Script para SIPLAN (Ubuntu/Laravel)
# ─────────────────────────────────────────────────────────────────────────────

# Colores para salida en consola
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Definir la ruta del proyecto
APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
TIMESTAMP=$(date '+%Y-%m-%d %H:%M:%S')

echo -e "${BLUE}========================================================================${NC}"
echo -e "${BLUE}   🏥  SIPLAN — DIAGNÓSTICO DE SALUD DEL SERVIDOR ($TIMESTAMP) ${NC}"
echo -e "${BLUE}========================================================================${NC}"

ERRORS_COUNT=0
WARNINGS_COUNT=0

# Detectar Sistema Operativo
IS_MAC=false
if [[ "$OSTYPE" == "darwin"* ]]; then
    IS_MAC=true
fi

# -----------------------------------------------------------------------------
# 1. ESTADO DE SERVICIOS DEL SISTEMA
# -----------------------------------------------------------------------------
echo -e "\n${YELLOW}▶ [1/6] Verificando Servicios del Servidor...${NC}"

check_service() {
    SERVICE_NAME=$1
    if [ "$IS_MAC" = true ]; then
        if pgrep -x "$SERVICE_NAME" > /dev/null || pgrep -f "$SERVICE_NAME" > /dev/null; then
            echo -e "  [✔] Servicio ${GREEN}$SERVICE_NAME${NC}: EN EJECUCIÓN (OK)"
        else
            echo -e "  [⚠️] Servicio ${YELLOW}$SERVICE_NAME${NC}: No detectado activo en Mac"
            ((WARNINGS_COUNT++))
        fi
    else
        if command -v systemctl >/dev/null 2>&1 && systemctl is-active --quiet "$SERVICE_NAME"; then
            echo -e "  [✔] Servicio ${GREEN}$SERVICE_NAME${NC}: EN EJECUCIÓN (OK)"
        else
            echo -e "  [❌] Servicio ${RED}$SERVICE_NAME${NC}: DETENIDO / ERROR"
            ((ERRORS_COUNT++))
        fi
    fi
}

check_service "nginx"
check_service "postgresql" || check_service "postgres"
check_service "redis"

# -----------------------------------------------------------------------------
# 2. VERIFICACIÓN DE CONEXIONES Y REDIS
# -----------------------------------------------------------------------------
echo -e "\n${YELLOW}▶ [2/6] Verificando Conexiones de Base de Datos y Cache...${NC}"

# Test Redis CLI
REDIS_PING=$(redis-cli ping 2>/dev/null)
if [ "$REDIS_PING" = "PONG" ]; then
    echo -e "  [✔] Redis Daemon: ${GREEN}PONG (Respondiendo correctamente)${NC}"
else
    echo -e "  [❌] Redis Daemon: ${RED}SIN RESPUESTA (Esperaba PONG, obtuvo: '$REDIS_PING')${NC}"
    ((ERRORS_COUNT++))
fi

# Test PostgreSQL via Artisan/PDO
if [ -f "$APP_DIR/artisan" ]; then
    DB_CHECK=$(cd "$APP_DIR" && php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'OK'; } catch(\Exception \$e) { echo 'FAIL: '.\$e->getMessage(); }" 2>/dev/null | grep -E "OK|FAIL" | tail -1)
    if [[ "$DB_CHECK" == *"OK"* ]]; then
        echo -e "  [✔] Conexión PostgreSQL (Laravel DB): ${GREEN}CONECTADO (OK)${NC}"
    else
        echo -e "  [❌] Conexión PostgreSQL (Laravel DB): ${RED}FALLÓ ($DB_CHECK)${NC}"
        ((ERRORS_COUNT++))
    fi
else
    echo -e "  [⚠️] No se pudo acceder a artisan en $APP_DIR"
    ((WARNINGS_COUNT++))
fi

# -----------------------------------------------------------------------------
# 3. RECURSOS DEL SERVIDOR (DISCO, MEMORIA Y CPU)
# -----------------------------------------------------------------------------
echo -e "\n${YELLOW}▶ [3/6] Evaluando Recursos del Servidor...${NC}"

# Uso de disco
DISK_USAGE=$(df -h "$APP_DIR" | awk 'NR==2 {print $(NF-1)}' | tr -d '%')
if [ -n "$DISK_USAGE" ]; then
    if [ "$DISK_USAGE" -gt 85 ]; then
        echo -e "  [❌] Disco duro: ${RED}${DISK_USAGE}% utilizado (ALERTA: Espacio crítico)${NC}"
        ((ERRORS_COUNT++))
    elif [ "$DISK_USAGE" -gt 70 ]; then
        echo -e "  [⚠️] Disco duro: ${YELLOW}${DISK_USAGE}% utilizado (ADVERTENCIA: Espacio reduciéndose)${NC}"
        ((WARNINGS_COUNT++))
    else
        echo -e "  [✔] Disco duro: ${GREEN}${DISK_USAGE}% utilizado (Espacio suficiente)${NC}"
    fi
fi

# Memoria RAM (Linux)
if command -v free >/dev/null 2>&1; then
    RAM_TOTAL=$(free -m | awk 'NR==2{print $2}')
    RAM_USED=$(free -m | awk 'NR==2{print $3}')
    RAM_FREE=$(free -m | awk 'NR==2{print $4}')
    echo -e "  [ℹ] Memoria RAM: ${RAM_USED}MB Usados / ${RAM_TOTAL}MB Totales (${RAM_FREE}MB Libres)"
fi

# Carga CPU
LOAD_AVG=$(uptime | awk -F'load average:' '{ print $2 }')
echo -e "  [ℹ] Carga promedio CPU:${LOAD_AVG}"

# -----------------------------------------------------------------------------
# 4. REVISIÓN DE LOGS DE ERRORES DE LARAVEL (ÚLTIMAS 24 HORAS)
# -----------------------------------------------------------------------------
echo -e "\n${YELLOW}▶ [4/6] Escaneando Logs de Errores de Laravel...${NC}"

LOG_PATH="$APP_DIR/storage/logs/laravel.log"
if [ -f "$LOG_PATH" ]; then
    TODAY_DATE=$(date '+%Y-%m-%d')
    RAW_ERRORS=$(grep -i "$TODAY_DATE" "$LOG_PATH" 2>/dev/null | grep -i -c -E "ERROR|CRITICAL|EMERGENCY|EXCEPTION" || echo "0")
    ERRORS_IN_LOG=$(echo "$RAW_ERRORS" | tr -cd '0-9')
    : "${ERRORS_IN_LOG:=0}"

    if [ "$ERRORS_IN_LOG" -gt 0 ]; then
        echo -e "  [⚠️] Se encontraron ${YELLOW}${ERRORS_IN_LOG} errores/excepciones registradas hoy${NC} en laravel.log"
        ((WARNINGS_COUNT++))
    else
        echo -e "  [✔] Logs de Laravel: ${GREEN}0 errores registrados el día de hoy${NC}"
    fi
else
    echo -e "  [ℹ] No se encontró laravel.log hoy en $LOG_PATH"
fi

# -----------------------------------------------------------------------------
# 5. PERMISOS Y ALMACENAMIENTO
# -----------------------------------------------------------------------------
echo -e "\n${YELLOW}▶ [5/6] Verificando Permisos de Escritura...${NC}"

check_writable() {
    DIR_PATH=$1
    if [ -w "$DIR_PATH" ]; then
        echo -e "  [✔] Escritura en $DIR_PATH: ${GREEN}OK${NC}"
    else
        echo -e "  [❌] Escritura en $DIR_PATH: ${RED}SIN PERMISOS DE ESCRITURA${NC}"
        ((ERRORS_COUNT++))
    fi
}

check_writable "$APP_DIR/storage"
check_writable "$APP_DIR/bootstrap/cache"

# -----------------------------------------------------------------------------
# 6. PRUEBA DE RESPUESTA WEB HTTP (END-TO-END)
# -----------------------------------------------------------------------------
echo -e "\n${YELLOW}▶ [6/6] Prueba de Respuesta Web HTTP...${NC}"

HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" -L http://127.0.0.1/ 2>/dev/null || echo "000")
if [ "$HTTP_CODE" = "000" ] || [ "$HTTP_CODE" = "404" ]; then
    # Probar endpoint secundario /login si el puerto por defecto o host varía
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" -L http://127.0.0.1/login 2>/dev/null || echo "000")
fi

if [ "$HTTP_CODE" = "200" ] || [ "$HTTP_CODE" = "302" ] || [ "$HTTP_CODE" = "404" ]; then
    echo -e "  [✔] Respuesta Web (HTTP 127.0.0.1): ${GREEN}HTTP $HTTP_CODE (Servidor web respondiendo correctamente)${NC}"
else
    echo -e "  [⚠️] Respuesta Web (HTTP 127.0.0.1): ${YELLOW}HTTP $HTTP_CODE${NC}"
    ((WARNINGS_COUNT++))
fi

# -----------------------------------------------------------------------------
# RESUMEN Y DIAGNÓSTICO FINAL
# -----------------------------------------------------------------------------
echo -e "\n${BLUE}========================================================================${NC}"
if [ "$ERRORS_COUNT" -eq 0 ] && [ "$WARNINGS_COUNT" -eq 0 ]; then
    echo -e "${GREEN}  🎉 DIAGNÓSTICO EXITOSO: El servidor y la aplicación están en estado PERFECTO.${NC}"
elif [ "$ERRORS_COUNT" -eq 0 ]; then
    echo -e "${YELLOW}  ⚠️ DIAGNÓSTICO COMPLETADO: Servidor operativo con $WARNINGS_COUNT advertencia(s).${NC}"
else
    echo -e "${RED}  🚨 ATENCIÓN REQUERIDA: Se detectaron $ERRORS_COUNT error(es) en el servidor.${NC}"
fi
echo -e "${BLUE}========================================================================${NC}\n"

exit $ERRORS_COUNT
