#!/bin/bash
# secure-traefik.sh - Configuration sécurisée de Traefik

# Créer des dossiers pour la configuration Traefik
mkdir -p /opt/temposwim/traefik

# Copier les fichiers de configuration
cp traefik/traefik-static.yml /opt/temposwim/traefik/
cp traefik/traefik-dynamic.yml /opt/temposwim/traefik/

# Modifier le fichier docker-stack.yml pour utiliser la configuration sécurisée
sed -i '/traefik:/,/command:/c\
  traefik:\
    image: traefik:v3.2\
    container_name: temposwim-traefik\
    restart: always\
    command:' /opt/temposwim/docker-stack.yml

# Ajouter les nouvelles commandes Traefik
sed -i '/- "--api.insecure=true"/c\
      - "--api=true"\
      - "--entrypoints.traefik.address=:8080"\
      - "--providers.file.directory=/etc/traefik/dynamic"\
      - "--providers.file.watch=true"' /opt/temposwim/docker-stack.yml

# Ajouter le volume pour les fichiers de configuration
sed -i '/\/var\/run\/docker.sock/a\
      - /opt/temposwim/traefik:/etc/traefik:ro' /opt/temposwim/docker-stack.yml

# Redémarrer Traefik
docker-compose -f /opt/temposwim/docker-stack.yml down traefik
docker-compose -f /opt/temposwim/docker-stack.yml up -d traefik

echo "Configuration sécurisée de Traefik terminée."