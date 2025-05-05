FROM nginx:alpine

# Installer Node.js et npm
RUN apk add --update nodejs npm

WORKDIR /app

# Copier et installer les dépendances
COPY package*.json ./
RUN npm install

# Copier le reste du code source
COPY . .

# Construire l'application
RUN npm run build

# Déplacer le contenu du build vers le répertoire nginx
# Cette commande va chercher soit un répertoire build soit un répertoire dist
RUN if [ -d "build" ]; then \
      cp -r build/* /usr/share/nginx/html/; \
    elif [ -d "dist" ]; then \
      cp -r dist/* /usr/share/nginx/html/; \
    else \
      echo "Aucun répertoire build ou dist trouvé" && \
      mkdir -p /usr/share/nginx/html && \
      echo "<html><body>Erreur de build</body></html>" > /usr/share/nginx/html/index.html; \
    fi

# Configurer Nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]