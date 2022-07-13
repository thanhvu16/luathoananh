<?php
?>

<div style="margin: 10px;background: #fff">
    <div style="padding: 10px;">
        <form method="POST" action="<?php echo \yii\helpers\Url::toRoute(['profile/change-password']) ?>">
            <input type="hidden" value="<?php echo Yii::$app->request->csrfToken ?>">
            <div class="form-group">
                <label for="passwordOld">Nhập Mật khẩu hiện tại</label>
                <input type="password" class="form-control" id="passwordOld" name="passwordCurrent" placeholder="Enter Password" maxlength="50" required>
            </div>
            <div class="form-group">
                <label for="passwordNew">Nhập Mật khẩu mới</label>
                <input type="password" class="form-control" id="passwordNew" placeholder="Enter New Password" name="passwordNew" maxlength="50" required>
            </div>
            <div class="form-group">
                <label for="passwordVerify">Xác nhận Mật khẩu mới</label>
                <input type="password" class="form-control" id="passwordVerify" placeholder="Enter Verify Password" name="passwordVerify" maxlength="50" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
