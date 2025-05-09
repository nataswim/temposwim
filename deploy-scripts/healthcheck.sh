#!/bin/bash
# healthcheck.sh - Vérification de la santé de l'application après déploiement

HEALTH_LOG="/opt/temposwim/health-check.txt"
echo "===== Vérification de santé: $(date) =====" >> $HEALTH_LOG

# Fonction pour journaliser les étapes
log_step() {
  echo "[$(date +%H:%M:%S)] $1" >> $HEALTH_LOG
  echo "$1"
}

# Vérification des conteneurs Docker
log_step "Vérification de l'état des conteneurs"
CONTAINERS=("temposwim-laravel" "temposwim-react" "temposwim-mysql" "temposwim-traefik")
ALL_RUNNING=true

for CONTAINER in "${CONTAINERS[@]}"; do
  STATUS=$(docker inspect --format='{{.State.Status}}' $CONTAINER 2>/dev/null)
  
  if [ "$STATUS" != "running" ]; then
    log_step "ERREUR: Le conteneur $CONTAINER n'est pas en cours d'exécution (Statut: $STATUS)"
    ALL_RUNNING=false
  else
    log_step "Le conteneur $CONTAINER est en cours d'exécution"
  fi
done

# Vérification des points d'accès Web
log_step "Vérification des points d'accès Web"

# Frontend
FRONTEND_STATUS=$(curl -s -o /dev/null -w "%{http_code}" -m 10 https://temposwim.com)
if [ "$FRONTEND_STATUS" -eq 200 ]; then
  log_step "Frontend accessible (HTTP 200)"
else
  log_step "ERREUR: Frontend inaccessible (HTTP $FRONTEND_STATUS)"
  ALL_RUNNING=false
fi

# Backend API
API_STATUS=$(curl -s -o /dev/null -w "%{http_code}" -m 10 https://api.temposwim.com/api/health)
if [ "$API_STATUS" -eq 200 ]; then
  log_step "API accessible (HTTP 200)"
else
  log_step "ERREUR: API inaccessible (HTTP $API_STATUS)"
  ALL_RUNNING=false
fi

# Résultat final
if [ "$ALL_RUNNING" = true ]; then
  log_step "SUCCÈS: Tous les services sont opérationnels"
  exit 0
else
  log_step "ÉCHEC: Certains services sont défaillants - Une intervention manuelle est nécessaire"
  exit 1
fi