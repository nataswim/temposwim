# Phase de build
FROM node:20-alpine AS build

WORKDIR /app

# Copier les fichiers de package
COPY package*.json ./
RUN npm ci

# Copier le code source
COPY . .

# Construction de l'application
RUN npm run build

# Phase de production
FROM nginx:alpine

# Copier les fichiers de build depuis l'étape de build
COPY --from=build /app/build /usr/share/nginx/html

# Copier la configuration Nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]