# Phase de build
FROM node:lts-alpine as build-stage

WORKDIR /app
COPY package*.json ./
RUN npm install

ARG MODE=test
COPY . .
COPY .env.${MODE} .env

RUN npm run build && ls -la

# Phase de production
FROM nginx:stable-alpine as production-stage
COPY --from=build-stage /app/build /usr/share/nginx/html

COPY nginx.conf /etc/nginx/conf.d/default.conf
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]