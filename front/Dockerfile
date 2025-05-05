# Phase de build
FROM node:lts-alpine as build-stage

WORKDIR /app
COPY package*.json ./
RUN npm install

ARG MODE=test
COPY . .
COPY .env.${MODE} .env

# Exécuter le build et afficher le contenu du répertoire
RUN npm run build && \
    echo "Contenu du répertoire après build:" && \
    ls -la && \
    echo "Sous-répertoires:" && \
    ls -la ./build || ls -la ./dist || echo "Aucun répertoire build ou dist trouvé"

# Phase de production
FROM nginx:stable-alpine as production-stage

# Utiliser "build" au lieu de "dist" (format Create React App)
COPY --from=build-stage /app/build /usr/share/nginx/html

# Si la ligne ci-dessus échoue, essayez de commenter cette ligne et décommenter la ligne suivante
# qui recherchera le répertoire de build au moment de l'exécution
# RUN mkdir -p /usr/share/nginx/html

# Copier le fichier de configuration Nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf
EXPOSE 80

# Script d'entrée pour chercher et copier le répertoire de build au démarrage
CMD ["nginx", "-g", "daemon off;"]