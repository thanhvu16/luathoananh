#!/bin/bash
count=`ps axu | grep fb_stats.sh | grep -v "grep" |wc -l`
if [ $count -ge 3  ] ; then
    echo "Service is running"
    exit 1
else
    php /srv/www/bongdanet/console/yii_test.php isport/stats
fi
