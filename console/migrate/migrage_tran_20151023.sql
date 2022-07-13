use vclip;
INSERT INTO user_monfee_surplus(
user_package_id,
expired_time,
promotion_monfee_price,
promotion_reset_day,
package_price_monfee,
type,
status,
thread_id,
page_id,
updated_time,
created_time,
charge_failed_daily,
vip
)
SELECT 
user_package_id,
expired_time,
promotion_monfee_price,
promotion_reset_day,
package_price_monfee,
type,
status,
thread_id,
page_id,
updated_time,
created_time,
charge_failed_daily,
vip
FROM user_monfee;

INSERT INTO vlive.`transaction`(
request_id,
package_id,
msisdn,
action,
object_id,
price,
created_time,
spec_page_id,
subscribe_status,
source_ads,
cp_id,
new_id
)
SELECT 
request_id,
package_id,
user_id,
action,
content_id,
price,
created_time,
page_id,
user_package_status,
source,
cp_id,
id
FROM vclip.`transaction` WHERE date(created_time) =date(now());
