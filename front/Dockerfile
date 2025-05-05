# Phase de build
FROM node:lts-alpine as build-stage

WORKDIR /app
COPY package*.json ./
RUN npm install

ARG MODE=test
COPY . ./
# Utiliser une instruction RUN au lieu de COPY avec OR
RUN if [ -f ".env.${MODE}" ]; then cp .env.${MODE} .env; else echo "No .env.${MODE} file found"; fi

# Build l'application - Create React App génère un dossier "build"
RUN npm run build

# Phase de production
FROM nginx:stable-alpine as production-stage

# IMPORTANT: Utiliser "build" et non "dist" pour Create React App
COPY --from=build-stage /app/build /usr/share/nginx/html/

# Utiliser RUN pour gérer la condition au lieu de COPY avec OR
RUN if [ -f "/app/nginx.conf" ]; then \
    cp /app/nginx.conf /etc/nginx/conf.d/default.conf; \
    else echo "nginx.conf not found, using default"; \
    fi

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]