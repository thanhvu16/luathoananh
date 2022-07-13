#!/bin/bash
count=`ps axu | grep livescore.sh | grep -v "grep" |wc -l`
if [ $count -ge 3  ] ; then
    echo "Service is running"
    exit 1
else
    php /srv/www/bongdanet/console/yii_test.php isport/livescore
fi
