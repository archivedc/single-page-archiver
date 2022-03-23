FROM ubuntu:focal

COPY genhash.sh /bin/genhash
COPY linkfile.sh /bin/linkfile
COPY doall.sh /bin/doall

RUN chmod +x /bin/genhash /bin/linkfile /bin/doall

VOLUME /data
WORKDIR /data