FROM alpine:3.4

COPY PhpTester.php /root

COPY PhpTesterTest.php /root

RUN apk update && \
    apk add --no-cache php=5.6.27-r0 && \
    cd /root && \
    echo "php -f /root/PhpTester.php \"\$@\"" > run.sh && \
    chmod 755 run.sh && \
    rm -rf /tmp/*
    
ENTRYPOINT ["/bin/sh", "/root/run.sh"]