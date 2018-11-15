FROM alpine:3.6
LABEL maintainer="Victor Castro-Cintas <victor.castro.cintas@gmail.com>"

{{#DOCKER_DEVBOX_CA_CERTIFICATES}}
COPY .ca-certificates/* /usr/local/share/ca-certificates/
RUN apk add --update ca-certificates
RUN update-ca-certificates
{{/DOCKER_DEVBOX_CA_CERTIFICATES}}

RUN apk add --update \
  ruby \
  ruby-dev \
  ruby-io-console \
  ruby-json \
  ruby-bigdecimal \
  bash \
  sqlite \
  sqlite-dev \
  openssl-dev \
  build-base

RUN gem install mailcatcher --no-rdoc --no-ri

# smtp port
EXPOSE 80 25

CMD ["mailcatcher", "-f", "--ip", "0.0.0.0", "--smtp-port", "25", "--http-port", "80"]
