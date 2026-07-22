FROM node:22-alpine AS builder

WORKDIR /app

COPY frontend/package.json frontend/package-lock.json* ./
RUN npm ci --legacy-peer-deps

COPY frontend/ .

RUN npm run build

FROM nginx:alpine

COPY --from=builder /app/dist /usr/share/nginx/html
COPY frontend/nginx-default.conf /etc/nginx/conf.d/default.conf
COPY frontend/public/service-worker.js /usr/share/nginx/html/service-worker.js

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
