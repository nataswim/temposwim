# =====================
# Phase de build
# =====================
FROM node:lts-alpine AS build-stage

# Définir le répertoire de travail
WORKDIR /app

# Copier les fichiers de dépendances
COPY package*.json ./

# Installer les dépendances
RUN npm install

# Passer l'argument de mode d'environnement (par défaut : test)
ARG MODE=test

# Copier l'ensemble du projet
COPY . ./

# Copier dynamiquement le fichier d'environnement s'il existe
RUN if [ -f ".env.${MODE}" ]; then cp ".env.${MODE}" .env; else echo "No .env.${MODE} file found"; fi

# Générer le build (CRA crée le dossier 'build')
RUN npm run build

# =====================
# Phase de production
# =====================
FROM nginx:stable-alpine AS production-stage

# Copier le build de l'étape précédente
COPY --from=build-stage /app/build /usr/share/nginx/html/

# Copier la configuration Nginx personnalisé
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Exposer le port
EXPOSE 80

# Lancer Nginx en mode non détaché
CMD ["nginx", "-g", "daemon off;"]
