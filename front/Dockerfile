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

# Copier tout le projet (y compris les fichiers d'environnement)
COPY . ./

# Copier dynamiquement le fichier d'environnement selon le mode (ex: .env.prod ou .env.test)
RUN if [ -f ".env.${MODE}" ]; then cp ".env.${MODE}" .env; else echo "No .env.${MODE} file found"; fi

# Générer le build (Create React App génère un dossier "build")
RUN npm run build

# =====================
# Phase de production
# =====================
FROM nginx:stable-alpine AS production-stage

# Copier le build généré dans le répertoire public de Nginx
COPY --from=build-stage /app/build /usr/share/nginx/html

# Copier la configuration personnalisée de Nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Exposer le port 80
EXPOSE 80

# Lancer Nginx au démarrage du conteneur
CMD ["nginx", "-g", "daemon off;"]
