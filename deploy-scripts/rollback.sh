#!/bin/bash
# rollback.sh - Script de restauration en cas d'échec du déploiement

# Variables
BACKUP_DIR="/opt/backups/temposwim"
ROLLBACK_LOG="/opt/temposwim/rollback-log.txt"
echo "===== Début du rollback: $(date) =====" >> $ROLLBACK_LOG

# Fonction pour journaliser les étapes
log_step() {
  echo "[$(date +%H:%M:%S)] $1" >> $ROLLBACK_LOG
  echo "$1"
}

# Notification de début
log_step "Démarrage du processus de rollback"

# Trouver la dernière sauvegarde
LATEST_BACKUP=$(ls -t $BACKUP_DIR/temposwim_db_*.sql.gz | head -1)
if [ -z "$LATEST_BACKUP" ]; then
  log_step "ERREUR: Aucune sauvegarde trouvée dans $BACKUP_DIR"
  exit 1
fi

log_step "Utilisation de la sauvegarde: $LATEST_BACKUP"

# Décompresser la sauvegarde
gunzip -c $LATEST_BACKUP > /tmp/rollback.sql

# Arrêter les conteneurs
log_step "Arrêt des conteneurs"
docker-compose -f /opt/temposwim/docker-stack.yml down >> $ROLLBACK_LOG 2>&1

# Si vous avez des tags spécifiques pour les versions stables
log_step "Retour aux images stables précédentes"
sed -i 's/:latest/:stable/g' /opt/temposwim/docker-stack.yml

# Redémarrer les conteneurs
log_step "Redémarrage des conteneurs avec les versions stables"
docker-compose -f /opt/temposwim/docker-stack.yml up -d >> $ROLLBACK_LOG 2>&1

# Attente pour que les conteneurs soient prêts
log_step "Attente de 10 secondes pour le démarrage des conteneurs"
sleep 10

# Restaurer la base de données
log_step "Restauration de la base de données"
cat /tmp/rollback.sql | docker exec -i temposwim-mysql mysql -u root -proot laravel >> $ROLLBACK_LOG 2>&1

# Recréer le lien symbolique de stockage
log_step "Recréation du lien symbolique de stockage"
docker exec temposwim-laravel php artisan storage:link >> $ROLLBACK_LOG 2>&1

# Vider les caches
log_step "Nettoyage du cache"
docker exec temposwim-laravel php artisan cache:clear >> $ROLLBACK_LOG 2>&1

# Notification de fin
log_step "Rollback terminé"
echo "===== Fin du rollback: $(date) =====" >> $ROLLBACK_LOG

# Restaurer le fichier docker-stack.yml
log_step "Restauration du fichier docker-stack.yml"
sed -i 's/:stable/:latest/g' /opt/temposwim/docker-stack.yml

exit 0