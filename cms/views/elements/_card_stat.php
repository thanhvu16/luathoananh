<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 6/21/2017
 * Time: 9:28 AM
 */
?>
<div class="alert alert-success col-md-6">
    <strong>Thông tin thẻ: </strong></br>
    <p>Tổng số thẻ: <?php echo (isset($cardStat['allTotal']))?$cardStat['allTotal']:'0'; ?></p>
    <p>Số số thẻ đã gửi thành công: <?php echo (isset($cardStat['successTotal']))?$cardStat['successTotal']:'0'; ?></p>
    <p>Số thẻ đang chờ gửi: <?php echo (isset($cardStat['waitingSendTotal']))?$cardStat['waitingSendTotal']:'0'; ?></p>
    <p>Số thẻ bao chưa gắn thuê bao: <?php echo (isset($cardStat['notHasPhoneTotal']))?$cardStat['notHasPhoneTotal']:'0'; ?></p>
    <p>&nbsp</p>
</div>