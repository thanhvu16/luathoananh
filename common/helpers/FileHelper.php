<?php

namespace common\helpers;

use Yii;
use yii\helpers\Html;
use yii\helpers\BaseFileHelper;

class FileHelper extends BaseFileHelper
{

    /**
     * @param $filename
     * @return string
     * @throws \yii\base\Exception
     */
    public static function getUploadTempPath($filename)
    {
        $path = Yii::getAlias('@uploadPath') . '/temp/';
        self::createDirectory($path);

        return static::_processUploadPath($path, $filename);
    }

    /**
     * @param $path
     * @return mixed
     */
    public static function getUploadTempUrl($path)
    {
        return str_replace(Yii::getAlias('@uploadPath') . '/temp/', '', $path);
    }

    /**
     * @param $filename
     * @return string
     */
    public static function getUploadTempPathExist($filename)
    {
        return Yii::getAlias('@uploadPath') . '/temp/' . $filename;
    }

    /**
     * @param null $filename
     * @return string
     * @throws \yii\base\Exception
     */
    public static function getUploadPath($filename = null,$idObjectName=null)
    {
        if(!empty($idObjectName)){
            $path = Yii::$app->session['upload_path'] . '/';
            self::createDirectory($path);
        }else{
            $path = Yii::getAlias('@uploadPath') . '/';
            $path .= date('Y/m/d/');
            self::createDirectory($path);
        }

        return static::_processUploadPath($path, $filename,$idObjectName);
    }

    /**
     * @param $path
     * @param $filename
     * @return string
     */
    private static function _processUploadPath($path, $filename,$idObjectName=null)
    {
        if(empty($idObjectName)){
            $fileInfo = pathinfo($filename);
            $filename = StringHelper::generateSlug($fileInfo['filename']);
            $extension = strtolower($fileInfo['extension']);
            $basename = $filename . '.' . $extension;
        }else{
            return $path . $idObjectName;
        }

        $newPath = $path . $basename;
        $newName = $basename;

        $counter = 1;
        while (file_exists($newPath)) {
            if ($counter > 10) {
                $counter = md5(microtime());
            }
            $newName = $filename . '-' . $counter . '.' . $extension;
            $newPath = $path . '/' . $newName;
            $counter++;
        }

        return $path . $newName;
    }

    /**
     * @param $path
     * @return string
     */
    public static function getUploadUrl($path)
    {
        return Yii::getAlias('@uploadUrl') . str_replace(Yii::getAlias('@uploadPath'), '', $path);
    }

    /**
     * @param $url
     * @return bool
     */
    public static function removeUploaded($url,$pathRemove=null)
    {
        if(!empty($pathRemove)){
            return self::removeFile($pathRemove . $url);
        }else{
            return self::removeFile(Yii::getAlias('@uploadPath') . $url);
        }
    }

    /**
     * @param $file
     * @return bool
     */
    public static function removeFile($file)
    {
        if (file_exists($file) && is_file($file)) {
            chmod($file, 0755);

            return (unlink($file)) ? true : false;
        } else return false;
    }

    /**
     * @return array
     */
    public static function getWhitelist()
    {
        $extWhitelist = array();
        foreach (static::listMimetype() as $key => $val) {
            $extWhitelist = array_merge($extWhitelist, array_keys($val));
        }

        return $extWhitelist;
    }

    /**
     * @return array
     */
    public static function listMimetype()
    {
        return array(
            'IMAGE' => array(
                'jpe' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpeg',
                'gif' => 'image/gif',
                'png' => 'image/png',
                'ico' => 'image/x-icon'
            ),
            'FLASH' => array(
                'swf' => 'application/x-shockwave-flash'
            ),
            'VIDEO' => array(
                'mp4' => 'video/mp4',
                'avi' => 'video/x-msvideo',
                'flv' => 'video/x-flv',
                'wmv' => 'video/x-ms-wmv',
                'mpeg' => 'video/mpeg'
            ),
            'AUDIO' => array(
                'wma' => 'audio/x-ms-wma'
            ),
            'ARCHIVE' => array(
                'zip' => 'application/zip',
                'rar' => 'application/x-rar-compressed',
                '7z' => 'application/x-7z-compressed',
                'gz' => 'application/x-gzip'
            ),
            'DOCUMENT' => array(
                'pdf' => 'application/pdf',
                'xps' => 'application/vnd.ms-xpsdocument',
                'prc' => 'application/x-mobipocket-ebook',
                'txt' => 'text/plain',
                'rtf' => 'application/rtf',
                'epub' => 'application/epub+zip',

                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',

                'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',

                'xls' => 'application/vnd.ms-excel',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

                'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',

                'ppt' => 'application/vnd.ms-powerpoint',
                'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',

                'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',

                'odt' => 'application/vnd.oasis.opendocument.text',
                'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
            ),
            'OTHER' => array(),
        );
    }

    /**
     * Lấy danh sách các folder trong folder
     * @param $dir
     * @param array $ignore
     * @return array
     */
    public static function findDirectories($dir, $ignore = array()) {
        $dirList = glob($dir . '/*' , GLOB_ONLYDIR);
        $result = array();
        foreach($dirList as $dir) {
            $name = basename($dir);
            if (!in_array($name, $ignore))
                $result[$name] = $name;
        }

        return $result;
    }

    /**
     * @param $path
     * @return int
     */
    public static function getDirectorySize($path)
    {
        $bytesTotal = 0;
        $path = realpath($path);
        if ($path !== false && is_dir($path)) {
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS)) as $object) {
                $bytesTotal += $object->getSize();
            }
        }

        return $bytesTotal;
    }

    /**
     * @param $size
     * @return string
     */
    public static function formatSize($size)
    {
        $mod = 1024;
        $units = explode(' ', 'B KB MB GB TB PB');
        for ($i = 0; $size > $mod; $i++) {
            $size /= $mod;
        }

        return number_format($size, 2) . ' ' . $units[$i];
    }

    /**
     * @param string $dir
     * @param array $options
     * @return bool
     */
    public static function removeDirectory($dir, $options = [])
    {
        parent::removeDirectory($dir, $options = []);

        return true;
    }

    /**
     * @param $msisdn
     * @return string
     */
    public static function formatPhoneNumber($msisdn)
    {
        if (preg_match("/^0[0-9]+$/", $msisdn))
            $msisdn = '84' . substr($msisdn, 1);

        return $msisdn;
    }

    /**
     * Function: sanitizeFilename
     * Returns a safe filename, by replacing all dangerous characters
     *
     * @param $string - The string to sanitize.
     * @param bool $forceLowercase - Force the string to lowercase?
     * @param bool $onlyAlphanumeric - If set to *true*, will remove all non-alphanumeric characters.
     * @param int $truncate - Number of characters to truncate to (default 100, 0 to disable).
     * @return mixed|string
     */
    public static function sanitizeFilename($string, $forceLowercase = true, $onlyAlphanumeric = false, $truncate = 100)
    {
        $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
            "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
            "—", "–", ",", "<", ".", ">", "/", "?");
        $clean = trim(str_replace($strip, "", strip_tags($string)));
        $clean = preg_replace('/\s+/', "-", $clean);
        $clean = ($onlyAlphanumeric) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean;
        $clean = ($truncate) ? substr($clean, 0, $truncate) : $clean;

        return ($forceLowercase) ? mb_strtolower($clean, 'UTF-8') : $clean;
    }

    /**
     * @param $grid
     * @param $label
     * @param array $url
     * @return string
     */
    public static function createPriorityLink($grid, $label, $url = [])
    {
        return Html::a($label, $url, [
            'class' => 'GridPrioritySave btn btn-white',
            'onclick' => "js:ajaxGridPriority('{$grid}', $(this).attr('href')); return false;"
        ]);
    }

    /**
     * @param $grid
     * @param $label
     * @param array $url
     * @return string
     */
    public static function createOrderLink($grid, $label, $url = [])
    {
        return Html::a($label, $url, [
            'class' => 'GridPrioritySave btn btn-white',
            'onclick' => "js:ajaxGridPriority('{$grid}', $(this).attr('href')); return false;"
        ]);
    }
}