FROM regexpress/base:latest

COPY PhpTester.php /root

COPY PhpTesterTest.php /root

RUN apk update && \
    apk add --no-cache php5=5.6.27-r0 php5-json=5.6.27-r0 && \
    cd /root && \
    echo "arg=();for var in \"\$@\";do arg+=(\$(echo -n \"\$var\" | base64 -d)); done; php -f /root/PhpTester.php \"\${arg[@]}\"" > run.sh && \
    chmod 755 run.sh && \
    rm -rf /tmp/*
    
ENTRYPOINT ["/bin/bash", "/root/run.sh"]