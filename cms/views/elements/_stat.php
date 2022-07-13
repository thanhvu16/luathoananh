<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 6/21/2017
 * Time: 9:28 AM
 */
?>
<div class="alert alert-info col-md-6">
    <strong>Thông tin thuê bao: </strong></br>
    <p>Tổng số thuê bao: <?php echo (isset($stat['allTotal']))?$stat['allTotal']:'0'; ?></p>
    <p>Số thuê bao đã gửi: <?php echo (isset($stat['successTotal']))?$stat['successTotal']:'0'; ?></p>
    <p>Số thuê bao đã gắn mã thẻ, đang chờ gửi: <?php echo (isset($stat['waitingSendTotal']))?$stat['waitingSendTotal']:'0'; ?></p>
    <p>Số thuê bao chưa gắn mã thẻ: <?php echo (isset($stat['notHasCardTotal']))?$stat['notHasCardTotal']:'0'; ?></p>
    <p>Số thuê bao gửi lỗi: <?php echo (isset($stat['errorTotal']))?$stat['errorTotal']:'0'; ?></p>
</div>