FROM node:8
LABEL maintainer="Victor Castro-Cintas <victor.castro-cintas@gfi.fr>"
{{#DOCKER_DEVBOX_COPY_CA_CERTIFICATES}}

COPY .ca-certificates/* /usr/local/share/ca-certificates/
RUN update-ca-certificates
ENV NODE_EXTRA_CA_CERTS=/etc/ssl/certs/ca-certificates.crt
{{/DOCKER_DEVBOX_COPY_CA_CERTIFICATES}}

# Mise à jour de npm
RUN npm i --global npm

# Allow nodeJS to run server on port < 1024
RUN apt-get update -y && apt-get install -y libcap2-bin && rm -rf /var/lib/apt/lists/* \
&& setcap 'cap_net_bind_service=+ep' $(which node)

# fixuid
ADD fixuid.tar.gz /usr/local/bin
RUN chown root:root /usr/local/bin/fixuid && \
    chmod 4755 /usr/local/bin/fixuid && \
    mkdir -p /etc/fixuid
COPY node/fixuid.yml /etc/fixuid/config.yml

WORKDIR /app

USER node

RUN yarn config set cafile ${NODE_EXTRA_CA_CERTS} --global
RUN npm config set prefix /home/node/.npm-packages
ENV PATH="${PATH}:/home/node/.npm-packages/bin"


RUN npm install -g @vue/cli

VOLUME /app
VOLUME /home/node/.cache
