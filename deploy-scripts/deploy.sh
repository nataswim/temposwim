#!/bin/bash
# deploy.sh - Script de déploiement automatisé pour TempoSwim

# Variables et journalisation
DEPLOY_LOG="/opt/temposwim/deploy-log.txt"
echo "===== Début du déploiement: $(date) =====" >> $DEPLOY_LOG

# Fonction pour journaliser les étapes
log_step() {
  echo "[$(date +%H:%M:%S)] $1" >> $DEPLOY_LOG
  echo "$1"
}

# Notification de début
log_step "Démarrage du processus de déploiement automatisé"

# Sauvegarde de la base de données avant déploiement
log_step "Sauvegarde de la base de données"
BACKUP_DIR="/opt/backups/temposwim"
mkdir -p $BACKUP_DIR
docker exec temposwim-mysql mysqldump -u root -proot laravel > $BACKUP_DIR/temposwim_db_$(date +%Y-%m-%d_%H-%M-%S).sql 2>> $DEPLOY_LOG
gzip $BACKUP_DIR/temposwim_db_*.sql 2>> $DEPLOY_LOG

# Récupération des nouvelles images Docker
log_step "Récupération des dernières images Docker"
docker-compose -f /opt/temposwim/docker-stack.yml pull >> $DEPLOY_LOG 2>&1

# Arrêt des conteneurs actuels
log_step "Arrêt des conteneurs existants"
docker-compose -f /opt/temposwim/docker-stack.yml down >> $DEPLOY_LOG 2>&1

# Démarrage des nouveaux conteneurs
log_step "Démarrage des nouveaux conteneurs avec les dernières images"
docker-compose -f /opt/temposwim/docker-stack.yml up -d >> $DEPLOY_LOG 2>&1

# Attente pour que les conteneurs soient prêts
log_step "Attente de 10 secondes pour le démarrage des conteneurs"
sleep 10

# Migration de la base de données
log_step "Exécution des migrations de base de données Laravel"
docker exec temposwim-laravel php artisan migrate --force >> $DEPLOY_LOG 2>&1

# Création du lien symbolique pour le stockage
log_step "Mise à jour du lien symbolique de stockage"
docker exec temposwim-laravel php artisan storage:link >> $DEPLOY_LOG 2>&1

# Nettoyage du cache Laravel
log_step "Nettoyage du cache Laravel"
docker exec temposwim-laravel php artisan cache:clear >> $DEPLOY_LOG 2>&1
docker exec temposwim-laravel php artisan config:cache >> $DEPLOY_LOG 2>&1
docker exec temposwim-laravel php artisan route:cache >> $DEPLOY_LOG 2>&1
docker exec temposwim-laravel php artisan view:cache >> $DEPLOY_LOG 2>&1

# Vérification de l'état des conteneurs
log_step "Vérification de l'état des conteneurs"
docker ps >> $DEPLOY_LOG 2>&1

# Nettoyage des anciennes images
log_step "Nettoyage des anciennes images non utilisées"
docker image prune -af >> $DEPLOY_LOG 2>&1

# Suppression des anciennes sauvegardes (plus de 30 jours)
log_step "Suppression des anciennes sauvegardes (plus de 30 jours)"
find $BACKUP_DIR -name "temposwim_db_*.sql.gz" -type f -mtime +30 -delete

# Vérification de la santé de l'application
log_step "Vérification de la santé de l'application"
/opt/temposwim/healthcheck.sh

# Si la vérification échoue, lancer le rollback
if [ $? -ne 0 ]; then
  log_step "ERREUR: La vérification de santé a échoué, lancement du rollback automatique"
  /opt/temposwim/rollback.sh
  log_step "Déploiement ÉCHOUÉ - Rollback effectué"
  exit 1
fi

# Notification de fin
log_step "Déploiement terminé avec succès"
echo "===== Fin du déploiement: $(date) =====" >> $DEPLOY_LOG

exit 0