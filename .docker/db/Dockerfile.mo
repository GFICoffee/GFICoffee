FROM mysql:5.7
LABEL maintainer="Victor Castro-Cintas <victor.castro-cintas@gfi.fr>"

# Mount this volume to help loading/exporting dumps
RUN mkdir /workdir
VOLUME /workdir

# fixuid
ADD fixuid.tar.gz /usr/local/bin
RUN chown root:root /usr/local/bin/fixuid && \
    chmod 4755 /usr/local/bin/fixuid && \
    mkdir -p /etc/fixuid
COPY db/fixuid.yml /etc/fixuid/config.yml

USER mysql
