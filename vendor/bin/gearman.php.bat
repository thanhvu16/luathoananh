@ECHO OFF
SET BIN_TARGET=%~dp0/../sinergi/gearman/bin/gearman.php
php "%BIN_TARGET%" %*
