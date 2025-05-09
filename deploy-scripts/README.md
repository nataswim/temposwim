Scripts et configurations de déploiement TempoSwim
Ce dossier contient tous les scripts et configurations nécessaires pour déployer l'application TempoSwim (React 19 + Laravel 11) sur un serveur VPS.
Table des matières
Fichiers inclus
Scripts de déploiement
Configurations Docker
Configuration Traefik
Installation sur le VPS
Prérequis
Étapes d'installation
Configuration du VPS
Prérequis
Installation des scripts de déploiement
Utilisation
Déploiement
Restauration
Vérification de la santé de l'application
Sécurisation de Traefik
Structure des fichiers
Dépendances
Contribution
Licence
Auteur
Fichiers inclus
Scripts de déploiement
deploy.sh - Script principal de déploiement :
Arrête les conteneurs Docker existants.
  Supprime les images Docker obsolètes.
  Lance un nouveau déploiement de l'application en utilisant Docker Compose.
  Effectue les migrations de la base de données Laravel.
  Efface le cache de l'application Laravel.
rollback.sh - Script de restauration en cas d'échec :
  Restaure la version précédente de l'application en cas d'échec du déploiement.
  Utilise les images Docker et les configurations sauvegardées.
healthcheck.sh - Script de vérification de la santé de l'application :
  Vérifie si l'application est en cours d'exécution et répond correctement.
  Peut être utilisé pour la surveillance et les alertes automatisées.
secure-traefik.sh - Script pour configurer Traefik de manière sécurisée :
  Configure Traefik pour utiliser HTTPS.
  Génère ou installe les certificats SSL/TLS.
  Met en place des middlewares de sécurité (par exemple, redirection HTTP vers HTTPS, en-têtes de sécurité).
Configurations Docker
docker-stack.yml - Configuration principale des services Docker :
  Définit les services nécessaires (par exemple, application Laravel, serveur web, base de données).
  Configure les réseaux, les volumes et les dépendances entre les services.
  Orchestre le déploiement de l'application avec Docker Compose.
Configuration Traefik
traefik/traefik-static.yml - Configuration statique de Traefik :
  Configure les paramètres globaux de Traefik (par exemple, fournisseurs, entrypoints).
  Définit les points d'entrée pour le trafic HTTP et HTTPS.
traefik/traefik-dynamic.yml - Configuration dynamique de Traefik :
  Configure le routage dynamique, les middlewares et les services.
  Définit comment Traefik gère les requêtes entrantes et les dirige vers les conteneurs appropriés.
  Peut inclure des règles de routage, l'équilibrage de charge et la terminaison SSL.
Installation sur le VPS
Prérequis
Ubuntu 24.04 LTS
Docker et Docker Compose installés
Accès SSH root au VPS
Un nom de domaine valide pointant vers l'adresse IP du VPS
Certificats SSL/TLS (recommandé, peut être généré par Let's Encrypt et configuré par Traefik)
Étapes d'installation
Connectez-vous à votre VPS via SSH.
Créez les répertoires nécessaires :
sudo mkdir -p /opt/temposwim
sudo mkdir -p /opt/backups/temposwim


Transférez les fichiers depuis votre machine locale :
# Sur votre machine locale, depuis le dossier du projet
cd deploy-scripts
tar -czf deploy-scripts.tar.gz *.sh README.md traefik docker-stack.yml
scp deploy-scripts.tar.gz root@votre_vps_ip:/tmp/

Remplacez votre_vps_ip par l'adresse IP réelle de votre VPS.
Sur le VPS, extrayez et configurez les scripts :
ssh root@votre_vps_ip
cd /opt/temposwim
tar -xzf /tmp/deploy-scripts.tar.gz
chmod +x *.sh
rm /tmp/deploy-scripts.tar.gz


Configurez et sécurisez Traefik :
./secure-traefik.sh


Déployez l'application :
./deploy.sh


Configuration du VPS pour TempoSwim
Prérequis
Ubuntu 24.04 LTS
Docker et Docker Compose installés
Accès SSH root
Installation des scripts de déploiement
Créez les répertoires nécessaires :
mkdir -p /opt/temposwim
mkdir -p /opt/backups/temposwim


Transférez les scripts depuis votre machine locale :
# Sur votre machine locale, depuis le dossier du projet
cd deploy-scripts
tar -czf deploy-scripts.tar.gz *.sh README.md traefik docker-stack.yml
scp deploy-scripts.tar.gz root@168.231.107.219:/tmp/

Assurez-vous de remplacer l'adresse IP par l'adresse IP de votre VPS.
Sur le VPS, extrayez et configurez les scripts :
cd /opt/temposwim
tar -xzf /tmp/deploy-scripts.tar.gz
chmod +x *.sh
rm /tmp/deploy-scripts.tar.gz


Utilisation
Déploiement
Pour déployer ou mettre à jour l'application, exécutez le script de déploiement :
./deploy.sh


Ce script arrêtera les conteneurs en cours d'exécution, supprimera les anciennes images, construira de nouvelles images et démarrera l'application avec la dernière configuration. Il exécutera également les migrations de la base de données Laravel et effacera le cache de l'application.
Restauration
En cas de problème lors du déploiement, vous pouvez utiliser le script de restauration pour revenir à la version précédente :
./rollback.sh


Ce script restaurera les conteneurs et les images de la sauvegarde précédente.
Vérification de la santé de l'application
Pour vérifier si l'application est en cours d'exécution et en bonne santé, utilisez le script de vérification de la santé :
./healthcheck.sh


Ce script effectuera une vérification de base pour s'assurer que l'application répond aux requêtes.
Sécurisation de Traefik
Pour configurer Traefik avec HTTPS et d'autres paramètres de sécurité, exécutez le script de sécurisation :
./secure-traefik.sh


Ce script configurera Traefik pour utiliser des certificats SSL/TLS (idéalement de Let's Encrypt) et appliquera les meilleures pratiques de sécurité.
Structure des fichiers
deploy-scripts/
├── deploy.sh
├── rollback.sh
├── healthcheck.sh
├── secure-traefik.sh
├── docker-stack.yml
├── README.md
└── traefik/
    ├── traefik-static.yml
    └── traefik-dynamic.yml


Dépendances
VPS avec Ubuntu 24.04 LTS
Docker
Docker Compose
Traefik (pour le reverse proxy et la gestion SSL)
Certificats SSL/TLS (recommandé)
PHP 8.2+
Laravel 11
Node.js 18+
React 19
MySQL 8+
Contribution
Les contributions sont les bienvenues ! Si vous avez des suggestions d'amélioration ou si vous souhaitez signaler un problème, veuillez ouvrir un problème ou soumettre une demande d'extraction.
Licence
Ce projet est sous licence Licence MIT.
Auteur
Nom : el haouat

Github : nataswim
