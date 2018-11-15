FROM node:carbon
LABEL maintainer="Victor Castro-Cintas <victor.castro.cintas@gmail.com>"

{{#DOCKER_DEVBOX_CA_CERTIFICATES}}
COPY .ca-certificates/* /usr/local/share/ca-certificates/
RUN update-ca-certificates
{{/DOCKER_DEVBOX_CA_CERTIFICATES}}

ENV NODE_EXTRA_CA_CERTS=/etc/ssl/certs/ca-certificates.crt

# Mise à jour de npm
RUN npm i --global npm

# Allow nodeJS to run server on port < 1024
RUN setcap 'cap_net_bind_service=+ep' $(which node)

# Fix permissions to match host user
RUN usermod -u ${HOST_UID:-1000} node && groupmod -g ${HOST_GID:-1000} node

RUN mkdir /home/node/.npm-packages && chown -R ${HOST_UID:-1000}:${HOST_GID:-1000} /home/node/.npm-packages
RUN mkdir -p /home/node/.cache/yarn && chown -R ${HOST_UID:-1000}:${HOST_GID:-1000} /home/node/.cache
RUN mkdir /app && chown -R ${HOST_UID:-1000}:${HOST_GID:-1000} /app

WORKDIR /app

USER node

RUN yarn config set cafile ${NODE_EXTRA_CA_CERTS} --global
RUN npm config set prefix /home/node/.npm-packages
ENV PATH="${PATH}:/home/node/.npm-packages/bin"

VOLUME /app
VOLUME /home/node/.npm-packages
VOLUME /home/node/.cache

