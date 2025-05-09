# Instructions d'installation des scripts de déploiement

Ces instructions expliquent comment installer les scripts de déploiement sur le VPS.

## Prérequis

- Accès SSH au VPS (root@168.231.107.219)
- Les conteneurs Docker déjà configurés et fonctionnels

## Installation

1. Transférer les scripts sur le VPS
   ```bash
   # Sur votre machine locale
   cd chemin/vers/temposwim
   tar -czf deploy-scripts.tar.gz deploy-scripts/
   scp deploy-scripts.tar.gz root@168.231.107.219:/tmp/