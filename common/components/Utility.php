<?php
/**
 * @Author: trinh.kethanh@gmail.com
 * @Date: 26/03/2015
 * @Function: Class xử lý các hàm tiện ích của hệ thống
 * @System: Video 2.0
 */

namespace common\components;

use garyjl\simplehtmldom\SimpleHtmlDom;
use Yii;
use yii\helpers\Json;

class Utility
{

    /**
     * @param $objID
     * @param bool $isDir
     * @return string
     */
    public static function storageSolutionEncode($objID, $isDir = false)
    {
        $step = 15; // Số bit để mã hóa thư mục trên cấp 1
        if ($objID >= 64912)
            $step = 10;
        $layer = 3; // Số cấp của thư mục
        $maxBits = PHP_INT_SIZE * 8;
        $result = '';
        for ($i = $layer; $i > 0; $i--) {
            $shift = $step * $i;
            $layerName = $shift <= $maxBits ? $objID >> $shift : 0;
            $result .= $isDir ? DS . $layerName : '/' . $layerName;
        }
        return $result;
    }

    /**
     * @param $clipId
     * @param bool $large
     * @return string
     */
    public static function makeVideoThumbnailUrl($objectClip, $large = false)
    {
        if ($objectClip) {
            $clipId = '';
            if (is_object($objectClip)) {
                $clipId = $objectClip->id;
            }
            if (is_array($objectClip)) {
                $clipId = $objectClip['id'];
            }

            //$time =0;
            //if(isset($objectClip->updated_time))
            //    $time       = strtotime($objectClip->updated_time);
            $filename = $clipId . '.jpg';
            if ($large)
                $filename = $clipId . '_large.jpg';
            return Yii::$app->params['img_clip']['data_url'] . '/' . Yii::$app->params['img_clip']['video'] . self::storageSolutionEncode($clipId) . '/' . $filename;

        }

        return null;
        //return Yii::$app->params['img_clip']['data_url'].Yii::$app->params['img_clip']['video'].'/no-image.jpg';
    }

    public static function makeThumbnailArray($objContent, $nameConfig, $large = false)
    {
        if ($objContent) {
            $objId = $objContent['id'];
            $time = 0;
            if (!empty($objContent['updated_time']))
                $time = strtotime($objContent['updated_time']);

            $dataPath = Yii::$app->params['img_url']['data_path'] . '/' . Yii::$app->params['img_url'][$nameConfig]['source'];
            $dataUrl = Yii::$app->params['img_url']['data_url'] . '/' . Yii::$app->params['img_url'][$nameConfig]['source'];

            $filename = $objId . '.png?v=' . $time;
            if ($large)
                $filename = $objId . '_large.jpg?v=' . $time;
            $dataPath = $dataPath . self::storageSolutionEncode($objId) . '/' . $filename;
            //if(file_exists($dataPath))
            return $dataUrl . self::storageSolutionEncode($objId) . '/' . $filename;
        }
        return '';
    }

    /**
     * @param $objId
     * @param $nameConfig
     * @param bool $large
     * @return string
     */
    public static function makeThumbnail($objContent, $nameConfig, $large = false)
    {
        if ($objContent) {
            $objId = $objContent->id;
            $time = 0;
            if (!empty($objContent->updated_time))
                $time = strtotime($objContent->updated_time);

            $dataPath = Yii::$app->params['img_url']['data_path'] . '/' . Yii::$app->params['img_url'][$nameConfig]['source'];
            $dataUrl = Yii::$app->params['img_url']['data_url'] . '/' . Yii::$app->params['img_url'][$nameConfig]['source'];

            $filename = $objId . '.png?v=' . $time;
            if ($large)
                $filename = $objId . '_large.jpg?v=' . $time;
            $dataPath = $dataPath . self::storageSolutionEncode($objId) . '/' . $filename;
            //if(file_exists($dataPath))
            return $dataUrl . self::storageSolutionEncode($objId) . '/' . $filename;
        }
        return '';
    }

    public static function makeImgNews($objContent, $nameConfig)
    {
        if ($objContent) {
            $timeImg = strtotime($objContent->created_time);
            if (isset($objContent->id) && $objContent->id < 67) {
                $timeImg += 7 * 60 * 60;
            }
            $time = 0;
            if (!empty($objContent->updated_time))
                $time = strtotime($objContent->updated_time);

            $dataUrl = Yii::$app->params['img_url']['data_url'] . Yii::$app->params['img_url'][$nameConfig]['source'] . '/' . date('Y/m/d', strtotime($objContent->created_time)) . '/';
            $fileName = $objContent->slug . '_' . $timeImg . '.jpg?v=' . $time;
            return $dataUrl . $fileName;
        }
        return '';
    }

    public static function makeImgNewsArray($arrContent, $nameConfig)
    {
        if ($arrContent) {
            $timeImg = strtotime($arrContent['created_time']);
            if (isset($arrContent['id']) && $arrContent['id'] < 67) {
                $timeImg += 7 * 60 * 60;
            }
            $time = 0;
            if (!empty($arrContent['updated_time']))
                $time = strtotime($arrContent['updated_time']);

            $dataUrl = Yii::$app->params['img_url']['data_url'] . Yii::$app->params['img_url'][$nameConfig]['source'] . '/' . date('Y/m/d', strtotime($arrContent['created_time'])) . '/';
            $fileName = $arrContent['slug'] . '_' . $timeImg . '.jpg?v=' . $time;
            return $dataUrl . $fileName;
        }
        return '';
    }

    public static function makeImage($objId, $nameConfig, $large = false)
    {
        if ($objId) {
            $dataPath = Yii::$app->params['img_url']['data_path'] . '/' . Yii::$app->params['img_url'][$nameConfig]['source'];
            $dataUrl = Yii::$app->params['img_url']['data_url'] . '/' . Yii::$app->params['img_url'][$nameConfig]['source'];
            $filename = md5($objId) . '.png';
            if ($large)
                $filename = md5($objId) . '_large.png';
            //$dataPath = $dataPath.self::storageSolutionEncode($objId).'/' . $filename;
            //if(file_exists($dataPath))
            return $dataUrl . self::storageSolutionEncode($objId) . '/' . $filename;

        }
        return '';
    }

    /**
     * @param $filmId
     * @param bool $large
     * @return string
     */
    public static function makeFilmThumbnailUrl($objectFilm, $large = false)
    {
        if ($objectFilm) {
            $filmId = $objectFilm->id;
            //$time =0;
            //if(isset($objectFilm->updated_time))
            //    $time       = strtotime($objectFilm->updated_time);
            //$filename = $filmId . '.jpg';
            //if ($large) {
            $filename = $filmId . '/large.jpg';
            return Yii::$app->params['img_film']['data_url'] . '/' . Yii::$app->params['img_film']['poster'] . '/' . $filename;
            //return Yii::$app->params['img_film']['data_url'] . '/' . Yii::$app->params['img_film']['poster'] . '/'. self::storageSolutionEncode($filmId). '/'. $filename;
            //return Yii::$app->params['img_film']['data_url'] . '/' . self::storageSolutionEncode($filmId). '/' . $filename;
            //} else {
            //    return Yii::$app->params['img_film']['data_url'] . '/'. Yii::$app->params['img_film']['poster'] . '/' . self::storageSolutionEncode($filmId) . '/' . $filename;
            //}
        }

        return Yii::$app->params['img_film']['data_url'] . Yii::$app->params['img_film']['poster'] . '/no-image.jpg';
    }

    /**
     * @param $clip
     * @return array|bool
     */
    public static function getThumbnailList($clip)
    {
        if ($clip) {
            $clipId = $clip->id;
            $thumbs = array();
            $baseDir = Yii::$app->params['img_clip']['data_path'] . Yii::$app->params['img_clip']['video'] . Utility::storageSolutionEncode($clipId);
            $baseURL = Yii::$app->params['img_clip']['data_url'] . '/' . Yii::$app->params['img_clip']['video'] . Utility::storageSolutionEncode($clipId) . '/' . $clipId;
            for ($i = 0; $i <= 9; $i++) {
                $thumbnailDir = $baseDir . $clipId . "-000" . $i . ".jpg";
                $thumbnailURL = "$baseURL/" . $clipId . "-000" . $i . ".jpg";
                $thumb['name'] = $clipId . "-000" . $i . ".jpg";
                $thumb['url'] = $thumbnailURL;
                $thumbs[] = $thumb;
            }
            return $thumbs;
        }
        return false;
    }

    /**
     * @param $clip
     * @return array|bool
     */
    public static function getFilmThumbnailList($clip)
    {
        if ($clip) {
            $clipId = $clip->id;
            $thumbs = array();
            $baseDir = Yii::$app->params['img_clip']['data_path'] . Yii::$app->params['img_clip']['video'] . Utility::storageSolutionEncode($clipId);
            $baseURL = Yii::$app->params['img_clip']['data_url'] . Yii::$app->params['img_clip']['video'] . Utility::storageSolutionEncode($clipId) . '/' . $clipId;
            for ($i = 0; $i <= 9; $i++) {
                $thumbnailDir = $baseDir . $clipId . "-000" . $i . ".jpg";
                $thumbnailURL = "$baseURL/" . $clipId . "-000" . $i . ".jpg";
                $thumb['name'] = $clipId . "-000" . $i . ".jpg";
                $thumb['url'] = $thumbnailURL;
                $thumbs[] = $thumb;
            }
            return $thumbs;
        }
        return false;
    }

    public static function makeNewVideoStreamingUrl($cmcId, $hq = null)
    {

        $filename = null;
        //if (isset($object->hq))
        //    $filename = $object->id."_level_2.mp4";
        //elseif($object->id > 190000) {
        //	$filename = $object->id."_level_2.mp4";
        //}
        if ($hq == 0) { //480
            $filename = $cmcId . "_level_2.mp4";
        } elseif ($hq && $hq == 1) {//360
            $filename = $cmcId . "_level_1.mp4";
        } elseif ($hq && $hq == 2) {//240
            $filename = $cmcId . ".mp4";
        } else
            $filename = $cmcId . "_level_2.mp4";
        $pathHash = Utility::vegaFilePathHash($cmcId);
        $config['nginx_key'] = 'ifa0e8f3fd';
        $config['nginx_prefix'] = '/';
        $file_sd = "/media1/" . $pathHash . "/" . $cmcId . "/" . $filename;
        $time = time() + 3600;
        $time = sprintf("%08x", $time);
        $md5hash_sd = md5($config['nginx_key'] . $file_sd . $time);
        $md5hash_sd = substr($md5hash_sd, 0, 8);

        $url = "http://media.mclip.vn" . $config['nginx_prefix'] . $md5hash_sd . '/' . $time . $file_sd;

        $http_host = 'http://media.mclip.vn/';
        $http_secret = 'wtfhe83fd2';
        $http_time = time() + 24 * 60 * 60;
        $http_hotkey_noip = str_replace('=', '', strtr(base64_encode(md5($http_secret . $file_sd . $http_time, TRUE)), '+/', '-_'));
        $url = $http_host . $http_hotkey_noip . '/' . $http_time . $file_sd;
        return $url;
    }

    /**
     * @param $object
     * @param null $hq
     * @return string
     */
    public static function makeVideoStreamingUrl($object, $hq = null)
    {
        if ($object->id) {
            return Yii::$app->params['video_clip']['data_url'] . self::storageSolutionEncode($object->id) . '/' . $object->id . '.mp4?v=' . time();

            if (!empty($_GET['streamx']) || (!empty($object->cmc_id) && !empty(Yii::$app->params['urlStreamVersion']) && Yii::$app->params['urlStreamVersion'] == 2)) {
                return self::makeNewVideoStreamingUrl($object->cmc_id, $hq);

            } else {
                $filename = null;
                //if (isset($object->hq))
                //    $filename = $object->id."_level_2.mp4";
                //elseif($object->id > 190000) {
                //	$filename = $object->id."_level_2.mp4";
                //}
                if ($hq == 0) { //480
                    $filename = $object->id . "_level_2.mp4";
                } elseif ($hq && $hq == 1) {//360
                    $filename = $object->id . "_level_1.mp4";
                } elseif ($hq && $hq == 2) {//240
                    $filename = $object->id . ".mp4";
                } else
                    $filename = $object->id . "_level_2.mp4";
                $pathHash = Utility::vegaFilePathHash($object->id);
                $config['nginx_key'] = 'ifa0e8f3fd';
                $config['nginx_prefix'] = '/';
                $file_sd = "/media4/" . $pathHash . "/" . $object->id . "/" . $filename;
                $time = time() + 3600;
                $time = sprintf("%08x", $time);
                $md5hash_sd = md5($config['nginx_key'] . $file_sd . $time);
                $md5hash_sd = substr($md5hash_sd, 0, 8);
                $url = "http://media.mclip.vn" . $config['nginx_prefix'] . $md5hash_sd . '/' . $time . $file_sd;
                return $url;
            }

        }
        return '';
    }

    /**
     * @param $number
     * @param bool $isDir
     * @return string
     */
    public static function vegaFilePathHash($number, $isDir = false)
    {
        $step = 15;   //so bit de ma hoa ten thu muc tren 1 cap
        if ($number >= 97657)
            $step = 10;
        $layer = 3;    //so cap thu muc
        $max_bits = PHP_INT_SIZE * 8;
        $result = "";

        for ($i = $layer; $i > 0; $i--) {
            $shift = $step * $i;
            $layer_name = $shift <= $max_bits ? $number >> $shift : 0;

            if ($i < $layer)
                $result .= $isDir ? DS : "/";

            $result .= $layer_name;
        }

        return $result;
    }

    /**
     * @param $clipID
     * @param $newThumbName
     * @return bool
     */
    public static function changeClipThumbnail($clipID, $newThumbName)
    {
        $baseDir = Yii::$app->params['img_clip']['data_path'] . '/' . Yii::$app->params['img_clip']['video'] . Utility::storageSolutionEncode($clipID);
        $fileSource = $baseDir . '/' . $clipID . '/' . $newThumbName;
        $fileDest = $baseDir . '/' . $clipID . '.jpg';
        $fileDestLarge = $baseDir . '/' . $clipID . '_large.jpg';
        if (!file_exists($baseDir . '/')) {
            mkdir($baseDir . '/', 0700, true);
        }
        if (!Utility::copyAndResizeImage($fileSource, $fileDest, 180, 150))
            return false;
        if (!Utility::copyAndResizeImage($fileSource, $fileDestLarge, 0, 0))
            return false;
        return true;
    }

    /**
     * @param $file
     * @param $target
     * @param $objId
     * @param array $options
     * @param bool $endcode
     * @return string
     */
    public static function uploadThumbnail($file, $target, $objId, $options = [], $endcode = false)
    {
        $success = false;
        $extension = 'jpg';
        $file_name = '00.' . $extension;
        $id = '00';
        $file_lage = '_large.' . $extension;
        if ($objId) {
            $file_name = $objId . '.' . $extension;
            $file_lage = $objId . '_large.' . $extension;
            $id = $objId;
        }

        $width = 120;
        $height = 160;
        if ($options) {
            $width = $options['width'];
            $height = $options['height'];
        }

        if ($endcode) {
            $imgEncode = $file;
            $data = str_replace('data:image/png;base64,', '', $imgEncode);
            $data = str_replace('data:image/jpeg;base64,', '', $data);
            $data = str_replace(' ', '+', $data);
            $data = base64_decode($data);
            if (!file_exists($target . self::storageSolutionEncode($id) . '/')) {
                mkdir($target . self::storageSolutionEncode($id) . '/', 0700, true);
            }
            $fileLage = $target . self::storageSolutionEncode($id) . '/' . $file_lage;
            if (is_file($fileLage))
                unlink($fileLage);
            file_put_contents($fileLage, $data);
            $fileThum = $target . self::storageSolutionEncode($id) . '/' . $file_name;
            if (is_file($fileThum))
                unlink($fileThum);
            file_put_contents($fileThum, $data);

            $success = Utility::copyAndResizeImage($fileThum, $fileThum, $width, $height);
            if ($success)
                return $file_name;
        } else {
            if ($file) {

                //var_dump($file);die;
                $dataPath = $target . self::storageSolutionEncode($id) . '/' . $file_name;

                if (!file_exists($target . self::storageSolutionEncode($id) . '/')) {
                    mkdir($target . self::storageSolutionEncode($id) . '/', 0700, true);
                }


                $fileThum = $target . self::storageSolutionEncode($id) . '/' . $file_name;
                if (is_file($fileThum))
                    unlink($fileThum);
                if (is_file($target)) {
                    unlink($target);
                }
                $fileLage = $target . self::storageSolutionEncode($id) . '/' . $file_lage;
                if (is_file($fileLage))
                    unlink($fileLage);

                $source = (isset($options['name'])) ? $file['tmp_name'][$options['name']] : $file['tmp_name'];
                if (move_uploaded_file($source, $fileLage))
                    $success = 1;

                if (move_uploaded_file($source, $fileThum))
                    $success = 1;

                Utility::copyAndResizeImage($fileLage, $fileLage, $width, $height);
                Utility::copyAndResizeImage($fileThum, $fileThum, $width, $height);
                if ($success)
                    return $file_name;

            }
        }
        return '';
    }
	
	public static function uploadImgAds($file, $obj, $lastDic = false)
	{
		$extension = 'jpg';
		if ($lastDic) {
			$directory = date('Y/m/d', strtotime($obj->created_time)) . '/';
			$fileName = $obj->id . '_' . strtotime($obj->created_time) . '.' . $extension;
			$fileUpload = Yii::$app->params['img_url']['data_path'] . 'ads/' . $directory . $fileName;
			$lastFile = Yii::$app->params['img_url']['data_path'] . 'ads/' . $directory . $lastDic . '_' . strtotime($obj->created_time) . '.' . $extension;
			if (is_file($lastFile)) {
				Utility::copyAndResizeImage($lastFile, $fileUpload);
				unlink($lastFile);
			}
			$obj->image = Yii::$app->params['img_url']['data_url'] . 'ads/' . $directory . $lastDic . '_' . strtotime($obj->created_time) . '.' . $extension;
			$obj->save(false);
		}
		$imgEncode = $file;
        $data = str_replace('data:image/png;base64,', '', $imgEncode);
        $data = str_replace('data:image/jpeg;base64,', '', $data);
        $data = str_replace(' ', '+', $data);
        $data = base64_decode($data);
        if (empty($obj) || empty($file))
            return false;
		$directory = date('Y/m/d', strtotime($obj->created_time)) . '/';
		if (!file_exists(Yii::$app->params['img_url']['data_path'] . 'ads/' . $directory)) {
			mkdir(Yii::$app->params['img_url']['data_path'] . 'ads/' . $directory, 0775, true);
		}
		$fileName = $obj->id . '_' . strtotime($obj->created_time) . '.' . $extension;
		$fileUpload = Yii::$app->params['img_url']['data_path'] . 'ads/' . $directory . $fileName;
		if (is_file($fileUpload))
			unlink($fileUpload);
		file_put_contents($fileUpload, $data);

		Utility::copyAndResizeImage($fileUpload, $fileUpload);
		$obj->image = Yii::$app->params['img_url']['data_url'] . 'ads/' . $directory . $fileName;
		$obj->save(false);
		return false;
	}

    public static function uploadImgNews($file, $obj, $lastDic = false)
    {
        $extension = 'jpg';
        $options = [
            Yii::$app->params['img_url']['news_img_options_large'],
            Yii::$app->params['img_url']['news_img_options_small'],
            Yii::$app->params['img_url']['news_img_options_medium'],
        ];
        if ($lastDic) {
            foreach ($options  as $k =>  $option) {
                $directory = date('Y/m/d', strtotime($obj->created_time)) . '/';
                $fileName = $obj->slug . '_' . strtotime($obj->created_time). $obj->id . '.' . $extension;
                $fileUpload = Yii::$app->params['img_url']['data_path'] . $option['source'] . '/' . $directory . $fileName;
                $lastFile = Yii::$app->params['img_url']['data_path'] . $option['source'] . '/' . $directory . $lastDic . '_' . strtotime($obj->created_time). $obj->id  . '.' . $extension;;
                if (is_file($lastFile)) {
                    Utility::copyAndResizeImage($lastFile, $fileUpload);
                    unlink($lastFile);
                }
				if($k == 0){
                    $obj->image = Yii::$app->params['img_url']['data_url'] . $option['source'] . '/' . $directory . $fileName;
                    $obj->save(false);
                }
            }
            return;
        }
        $imgEncode = $file;
        $data = str_replace('data:image/png;base64,', '', $imgEncode);
        $data = str_replace('data:image/jpeg;base64,', '', $data);
        $data = str_replace(' ', '+', $data);
        $data = base64_decode($data);
        if (empty($obj) || empty($file))
            return false;
        foreach ($options  as $k =>  $option) {
            $directory = date('Y/m/d', strtotime($obj->created_time)) . '/';
            if (!file_exists(Yii::$app->params['img_url']['data_path'] . $option['source'] . '/' . $directory)) {
                mkdir(Yii::$app->params['img_url']['data_path'] . $option['source'] . '/' . $directory, 0755, true);
            }
            $slug = $obj->slug ?? '';
            $fileName = $slug . '_' . strtotime($obj->created_time). $obj->id  . '.' . $extension;
            $fileUpload = Yii::$app->params['img_url']['data_path'] . $option['source'] . '/' . $directory . $fileName;
            if (is_file($fileUpload))
                unlink($fileUpload);
            file_put_contents($fileUpload, $data);

            Utility::copyAndResizeImage($fileUpload, $fileUpload);
			if($k == 0){
                $obj->image = Yii::$app->params['img_url']['data_url'] . $option['source'] . '/' . $directory . $fileName;
                $obj->save(false);
            }
        }
        return false;
    }

    public static function uploadFilex($file, $target, $object, $extension, $options = array(), $endcode = false)
    {
        $fileName = isset($object->id) ? $object->id . '.' . $extension : $object . '.' . $extension;
        $upload = $target . '/' . $fileName;

        if ($endcode) {
            $width = 120;
            $height = 120;
            if ($options) {
                $width = $options['width'];
                $height = $options['height'];
            }

            $imgEncode = $file;
            $data = str_replace('data:image/png;base64,', '', $imgEncode);
            $data = str_replace('data:image/jpeg;base64,', '', $data);
            $data = str_replace(' ', '+', $data);
            $data = base64_decode($data);
            if (!file_exists($target)) {
                mkdir($target, 0755, true);
            }

            if (is_file($upload))
                unlink($upload);
            file_put_contents($upload, $data);

            $success = Utility::copyAndResizeImage($upload, $upload, $width, $height);
            if ($success)
                return $fileName;
        }

        if ($file) {
            if (!file_exists($target)) {
                mkdir($target, 0755, true);
            }
            if (is_file($upload))
                unlink($upload);

            $source = $file['tmp_name'];

            if ($options) {
                $width = $options['width'];
                $height = $options['height'];
                move_uploaded_file($source, $upload);
                $success = Utility::copyAndResizeImage($upload, $upload, $width, $height);
                if ($success)
                    return $fileName;
            } else {
                if (move_uploaded_file($source, $upload))
                    return $fileName;
            }
        }

        return null;

    }

    public static function uploadFile($file, $target, $object)
    {
        $success = false;
        if ($file) {
            $fileName = $object->id . '_' . $file['name']['source_file'];
            $upload = $target . '/' . $fileName;
            if (is_file($upload))
                unlink($upload);
            $source = $file['tmp_name']['source_file'];
            if (move_uploaded_file($source, $upload))
                return $fileName;
        }

        return null;

    }

    public static function uploadTextFile($file, $target, $object, $extension)
    {
        $fileName = isset($object->id) ? $object->id . '.' . $extension : $object . '.' . $extension;
        $upload = $target . '/' . $fileName;

        if ($file) {
            if (!file_exists($target)) {
                mkdir($target, 0755, true);
            }
            if (is_file($upload))
                unlink($upload);

            $source = $file['tmp_name']['source_file'];

            if (move_uploaded_file($source, $upload))
                return $fileName;
        }

        return null;

    }

    public static function uploadThumbnailFilm($file, $target, $objId, $options = [], $endcode = false)
    {
        $success = false;

        $id = '00';
        if ($objId) {
            //$file_name = $objId.'.' . $extension;
            //$file_lage = $objId.'_large.' . $extension;
            $id = $objId;
        }

        $extension = 'jpg';
        $file_name = $id . '.' . $extension;
        $file_lage = $id . '_large.' . $extension;


        $width = 120;
        $height = 160;
        if ($options) {
            $width = $options['width'];
            $height = $options['height'];
        }

        if ($endcode) {
            $imgEncode = $file;
            $data = str_replace('data:image/png;base64,', '', $imgEncode);
            $data = str_replace('data:image/jpeg;base64,', '', $data);
            $data = str_replace(' ', '+', $data);
            $data = base64_decode($data);
            if (!file_exists($target . self::storageSolutionEncode($id) . '/')) {
                //mkdir($target.$id. '/', 0700, true);
                mkdir($target . self::storageSolutionEncode($id) . '/', 0700, true);
            }
            $fileLage = $target . '/' . self::storageSolutionEncode($id) . '/' . $file_lage;
            if (is_file($fileLage))
                unlink($fileLage);
            file_put_contents($fileLage, $data);
            $fileThum = $target . '/' . self::storageSolutionEncode($id) . '/' . $file_name;
            if (is_file($fileThum))
                unlink($fileThum);
            file_put_contents($fileThum, $data);

            $success = Utility::copyAndResizeImage($fileThum, $fileThum, $width, $height);
            if ($success)
                return $file_name;
        } else {
            if ($file) {
                $dataPath = $target . '/' . self::storageSolutionEncode($id) . '/' . $file_name;

                if (!file_exists($target . self::storageSolutionEncode($id) . '/')) {
                    mkdir($target . '/' . self::storageSolutionEncode($id) . '/', 0700, true);
                }


                $fileThum = $target . '/' . self::storageSolutionEncode($id) . '/' . $file_name;
                if (is_file($fileThum))
                    unlink($fileThum);
                if (is_file($target)) {
                    unlink($target);
                }
                $fileLage = $target . '/' . self::storageSolutionEncode($id) . '/' . $file_lage;
                if (is_file($fileLage))
                    unlink($fileLage);

                $source = $file['tmp_name'];
                if (move_uploaded_file($source, $fileLage))
                    $success = 1;

                if (move_uploaded_file($source, $fileThum))
                    $success = 1;

                if ($success)
                    return $file_name;
            }
        }
        return '';
    }


    public static function uploadImage($file, $target, $objId, $options = [])
    {
        $extension = 'png';
        $file_name = '00.' . $extension;
        $id = '00';
        if ($objId) {
            $id = $objId;
            $file_name = md5($objId) . '.' . $extension;
        }
        if ($file) {
            $dataPath = $target . self::storageSolutionEncode($id) . '/' . $file_name;
            if (!file_exists($target . self::storageSolutionEncode($id) . '/')) {
                mkdir($target . self::storageSolutionEncode($id) . '/', 0755, true);
            }

            $fileThum = $target . self::storageSolutionEncode($id) . '/' . $file_name;
            if (is_file($fileThum))
                unlink($fileThum);
            if (is_file($target)) {
                unlink($target);
            }
            $source = $file['tmp_name'];


            if (move_uploaded_file($source, $fileThum))
                $success = 1;
            if ($options['width'] && $options['height']) {
                $arrSize[0] = $options['width'];
                $arrSize[1] = $options['height'];
                return Utility::cropImage($fileThum, $arrSize, $fileThum);
            }
            if ($success)
                return $file_name;
        }
        return '';
    }

    public static function cropImage($imgSrc, $arrSize, $imgTarget = null)
    {
        $thumbnail_width = $arrSize[0];
        $thumbnail_height = $arrSize[1];
        if (!$imgTarget)
            $imgTarget = $imgSrc;
        $newThumb = Utility::__getThumbnailResource($imgSrc, $thumbnail_width, $thumbnail_height);
        $ext = substr($imgTarget, -4);
        if ($ext == '.gif') {
            imagegif($newThumb, $imgTarget);
        } elseif ($ext == '.jpg' || $ext == 'jpeg') {
            imagejpeg($newThumb, $imgTarget);
        } elseif ($ext == '.png') {
            imagepng($newThumb, $imgTarget);
        } else {
            return false;
        }

        #die($imgTarget);
        return $imgTarget;
    }

    public static function __getThumbnailResource($imgSrc, $thumbnail_width, $thumbnail_height)
    {
        $arrInfo = getimagesize($imgSrc);
        $width_orig = $arrInfo[0];
        $height_orig = $arrInfo[1];
        $lmime = strtolower($arrInfo['mime']);

        if (strpos($lmime, 'gif') !== false) {
            $myImage = imagecreatefromgif($imgSrc);
        } elseif (strpos($lmime, 'png') !== false) {
            $myImage = imagecreatefrompng($imgSrc);
        } else {
            $myImage = imagecreatefromjpeg($imgSrc);
        }

        if ($thumbnail_height >= $height_orig && $thumbnail_width >= $width_orig) {
            return $myImage;
        }

        $ratio_orig = $width_orig / $height_orig;

        if ($thumbnail_height == 0) {
            $new_width = $thumbnail_width;
            $new_height = $thumbnail_height = $thumbnail_width / $ratio_orig;
        } elseif ($thumbnail_width) {
            $new_width = $thumbnail_width = $thumbnail_height * $ratio_orig;
            $new_height = $thumbnail_height;
        } elseif ($thumbnail_width / $thumbnail_height > $ratio_orig) {
            $new_height = $thumbnail_width / $ratio_orig;
            $new_width = $thumbnail_width;
        } elseif ($thumbnail_width / $thumbnail_height < $ratio_orig) {
            $new_width = $thumbnail_height * $ratio_orig;
            $new_height = $thumbnail_height;
        } else {
            $new_width = $thumbnail_width;
            $new_height = $thumbnail_height;
        }

        $x_mid = $new_width / 2; //horizontal middle
        $y_mid = $new_height / 2; //vertical middle

        $process = imagecreatetruecolor(round($new_width), round($new_height));

        imagecopyresampled($process, $myImage, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);
        $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
        imagecopyresampled($thumb, $process, 0, 0, ($x_mid - ($thumbnail_width / 2)), ($y_mid - ($thumbnail_height / 2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);

        imagedestroy($process);
        imagedestroy($myImage);
        return $thumb;
    }

    /**
     * @param $fileSource
     * @param $fileDest
     * @param int $newWidth
     * @param int $newHeight
     * @return bool
     */
    public static function copyAndResizeImage($fileSource, $fileDest, $newWidth = 0, $newHeight = 0)
    {
        if (!file_exists($fileSource)) {
            return false;
        }

        $sourceFileInfo = getimagesize($fileSource);
        $width = $sourceFileInfo[0];
        $height = $sourceFileInfo[1];
        $mime = $sourceFileInfo["mime"];
        if (!$newWidth) $newWidth = $width;
        if (!$newHeight) $newHeight = $height;

        $xR = $newWidth / $width;
        $yR = $newHeight / $height;
        if ($xR < $yR) {
            $newHeight = floor($height * $xR);
        } elseif ($xR > $yR) {
            $newWidth = floor($width * $yR);
        }

        $thumbnail = imagecreatetruecolor($newWidth, $newHeight);

        switch ($mime) {
            case "image/jpeg":
                $source = imagecreatefromjpeg($fileSource);
                break;
            case "image/gif":
                $source = imagecreatefromgif($fileSource);
                break;
            case "image/png":
                $source = imagecreatefrompng($fileSource);
                break;
        }
        $white = imagecolorallocate($thumbnail, 255, 255, 255);
        imagefill($thumbnail, 0, 0, $white);
        if (!imagecopyresized($thumbnail, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height)) {
            return false;
        }
        if (!imagejpeg($thumbnail, $fileDest, 70)) {
            return false;
        }
        if (!imagedestroy($source)) return false;
        return true;
    }

    /**
     * @param $s
     * @return string
     */
    public static function secondToTime($s)
    {
        $hour = floor($s / 3600);
        $minute = floor(($s - $hour * 3600) / 60);
        $second = $s - $hour * 3600 - $minute * 60;

        if ($hour == 0) {
            $hour = '';
        } else if ($hour < 10) {
            $hour = '0' . $hour . ':';
        } else {
            $hour = $hour . ':';
        }

        if ($minute == 0) {
            $minute = '00:';
        } else if ($minute < 10) {
            $minute = '0' . $minute . ':';
        } else {
            $minute = $minute . ':';
        }

        if ($second == 0) {
            $second = '00';
        } else if ($second < 10) {
            $second = '0' . $second;
        }

        return $hour . $minute . $second;
    }

    /**
     * @param $string
     * @return mixed
     */
    public static function stripText($string)
    {
        $from = array("à", "ả", "ã", "á", "ạ", "ă", "ằ", "ẳ", "ẵ", "ắ", "ặ", "â", "ầ", "ẩ", "ẫ", "ấ", "ậ", "đ", "è", "ẻ", "ẽ", "é", "ẹ", "ê", "ề", "ể", "ễ", "ế", "ệ", "ì", "ỉ", "ĩ", "í", "ị", "ò", "ỏ", "õ", "ó", "ọ", "ô", "ồ", "ổ", "ỗ", "ố", "ộ", "ơ", "ờ", "ở", "ỡ", "ớ", "ợ", "ù", "ủ", "ũ", "ú", "ụ", "ư", "ừ", "ử", "ữ", "ứ", "ự", "ỳ", "ỷ", "ỹ", "ý", "ỵ", "À", "Ả", "Ã", "Á", "Ạ", "Ă", "Ằ", "Ẳ", "Ẵ", "Ắ", "Ặ", "Â", "Ầ", "Ẩ", "Ẫ", "Ấ", "Ậ", "Đ", "È", "Ẻ", "Ẽ", "É", "Ẹ", "Ê", "Ề", "Ể", "Ễ", "Ế", "Ệ", "Ì", "Ỉ", "Ĩ", "Í", "Ị", "Ò", "Ỏ", "Õ", "Ó", "Ọ", "Ô", "Ồ", "Ổ", "Ỗ", "Ố", "Ộ", "Ơ", "Ờ", "Ở", "Ỡ", "Ớ", "Ợ", "Ù", "Ủ", "Ũ", "Ú", "Ụ", "Ư", "Ừ", "Ử", "Ữ", "Ứ", "Ự", "Ỳ", "Ỷ", "Ỹ", "Ý", "Ỵ", "+", ".", "&", "è", "ậ", "ị");
        $to = array("a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "d", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "i", "i", "i", "i", "i", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "y", "y", "y", "y", "y", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "D", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "I", "I", "I", "I", "I", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "Y", "Y", "Y", "Y", "Y", "_", "_", "_", "e", "a", "i");
        return str_replace($from, $to, $string);
    }

    public static function getSelect($array = null)
    {
        $selections = [];
        foreach ($array as $key => $item) {
            $selections[$item] = ['selected' => 'selected'];
        }
        return $selections;
    }

    /**
     * @param $string
     * @return mixed|string
     */
    public static function rewrite($string)
    {
        $string = Utility::stripText($string);
        $string = strtolower(trim($string));
        $string = preg_replace('/[^a-z0-9-]/', '-', $string);
        $string = preg_replace('/-+/', "-", $string);
        return $string;
    }

    /*
     * @param $val
     * @param $ky
     *
     */
    public static function decryptAES128($val, $ky)
    {
        $key = "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0";
        for ($a = 0; $a < strlen($ky); $a++)
            $key[$a % 16] = chr(ord($key[$a % 16]) ^ ord($ky[$a]));
        $mode = MCRYPT_MODE_ECB;
        $enc = MCRYPT_RIJNDAEL_128;

        $val = base64_decode($val);

        $dec = @mcrypt_decrypt($enc, $key, $val, $mode, @mcrypt_create_iv(@mcrypt_get_iv_size($enc, $mode), MCRYPT_DEV_URANDOM));
        return rtrim($dec, ((ord(substr($dec, strlen($dec) - 1, 1)) >= 0 and ord(substr($dec, strlen($dec) - 1, 1)) <= 16) ? chr(ord(substr($dec, strlen($dec) - 1, 1))) : null));
    }

    public static function encryptAES128($val, $ky)
    {
        $key = "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0";
        for ($a = 0; $a < strlen($ky); $a++)
            $key[$a % 16] = chr(ord($key[$a % 16]) ^ ord($ky[$a]));
        $mode = MCRYPT_MODE_ECB;
        $enc = MCRYPT_RIJNDAEL_128;
        //$val=str_pad($val, (16*(floor(strlen($val) / 16)+(strlen($val) % 16==0?2:1))), chr(16-(strlen($val) % 16)));
        return base64_encode(mcrypt_encrypt($enc, $key, $val, $mode, mcrypt_create_iv(mcrypt_get_iv_size($enc, $mode), MCRYPT_DEV_URANDOM)));
    }

    public static function formatMsisdn84($msisdn, $stripArray = "84,0", $prefix = "84")
    {
        foreach (explode(",", $stripArray) as $item) {
            $length = strlen($item);
            if (substr($msisdn, 0, $length) === $item) {
                $msisdn = substr($msisdn, strlen($item));
            }
        }
        return trim($prefix . $msisdn);

        $msisdn = trim($prefix . $msisdn);

        $mapConfig = [
            /*'84162' => '8432',
            '84163' => '8433',
            '84164' => '8434',
            '84165' => '8435',
            '84166' => '8436',
            '84167' => '8437',
            '84168' => '8438',
            '84169' => '8439',*/

            '8432' => '84162',
            '8433' => '84163',
            '8434' => '84164',
            '8435' => '84165',
            '8436' => '84166',
            '8437' => '84167',
            '8438' => '84168',
            '8439' => '84169',
        ];

        foreach ($mapConfig as $k => $v) {
            $len = strlen($k);
            if (substr($msisdn, 0, $len) == $k) {
                $msisdn = substr_replace($msisdn, $v, 0, $len);
                break;
            }
        }

        return $msisdn;
    }

    public static function mapUserPhone2($msisdn, $type = 1)
    {
        $msisdn = Utility::formatMsisdn84($msisdn);

        if ($type == 1) {
            $mapConfig = [
                '84162' => '8432',
                '84163' => '8433',
                '84164' => '8434',
                '84165' => '8435',
                '84166' => '8436',
                '84167' => '8437',
                '84168' => '8438',
                '84169' => '8439',

                '84120' => '8470',
                '84121' => '8479',
                '84122' => '8477',
                '84126' => '8476',
                '84128' => '8478',

                '84124' => '8484',
                '84127' => '8481',
                '84129' => '8482',
                '84123' => '8483',
                '84125' => '8485'
            ];
        } else {
            $mapConfig = [
                '8432' => '84162',
                '8433' => '84163',
                '8434' => '84164',
                '8435' => '84165',
                '8436' => '84166',
                '8437' => '84167',
                '8438' => '84168',
                '8439' => '84169',

                '8470' => '84120',
                '8479' => '84121',
                '8477' => '84122',
                '8476' => '84126',
                '8478' => '84128',

                '8484' => '84124',
                '8481' => '84127',
                '8482' => '84129',
                '8483' => '84123',
                '8485' => '84125'


            ];
        }


        foreach ($mapConfig as $k => $v) {
            $len = strlen($k);
            if (substr($msisdn, 0, $len) == $k) {
                $msisdn = substr_replace($msisdn, $v, 0, strlen($k));
                break;
            }
        }

        return $msisdn;
    }

    public static function formatMsisdn84_ERROR($msisdn)
    {
        $msisdn = \vega\telco\Telco::normalizeMSISDN($msisdn, '9', '849');
        //$msisdn = \vega\telco\Telco::normalizeMSISDN($msisdn, '8', '848');
        $msisdn = \vega\telco\Telco::normalizeMSISDN($msisdn, '1', '841');
        $msisdn = str_replace('+', '', $msisdn);
        $msisdn = \vega\telco\Telco::normalizeMSISDN($msisdn, '0084', '84');
        return \vega\telco\Telco::normalizeMSISDN($msisdn, '0', '84');
    }

    public static function is_image($path)
    {
        $a = getimagesize($path);
        $image_type = $a[2];

        if (in_array($image_type, array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP))) {
            return true;
        }
        return false;
    }

    public static function renderJson($data = array())
    {
        //header_remove();
        header('Content-type:application/json; charset=utf-8');
        if (IS_PRODUCTION) {
            echo Json::encode($data);
        } else {
            echo Json::encode($data, JSON_PRETTY_PRINT);
        }
        exit;
    }

    public static function getInt($val, $defaultVal = 0)
    {
        if (isset($val) && !empty($val)) {
            return intval($val);
        }

        return $defaultVal;
    }

    public static function genTagsByKeywords($string)
    {
        $string = self::stripText($string);
        $string = strtolower(trim($string));
        $string = preg_replace('/[^a-z0-9- -,]/', '-', $string);
        $string = preg_replace('/-+/', " ", $string);
        return $string;
    }

    public static function isActiveGameQuiz($msisdn)
    {
        $game_quiz_active = Yii::$app->params['game-quiz']['active'];
        $game_quiz_white_list_mode = Yii::$app->params['game-quiz']['white_list_mode'];
        $game_quiz_white_phone = Yii::$app->params['game-quiz']['white_list_phone'];
        if ($game_quiz_active) {
            if ($game_quiz_white_list_mode == false || ($game_quiz_white_list_mode == true && in_array($msisdn, $game_quiz_white_phone))) {
                return true;
            }
        }
        return false;

    }


    public static function convertContentAmp($content)
    {
        if (empty($content))
            return '';
        $dom = SimpleHTMLDom::str_get_html($content);

        foreach ($dom->find('img') as $k => $v) {
            $img = $v->src;
            $infoImg = getimagesize($img);
            if (!empty($infoImg)) {
                $v->width = $infoImg[0];
                $v->height = $infoImg[1];
            }
        }
        foreach ($dom->find('table') as $k => $v) {
            $v->removeAttribute('border');
        }
        $content = $dom;
        $content = str_replace('http://media.keobongda123.com', 'https://media.keobongda123.com', $content);
        $content = preg_replace('/(\<img[^>]+)(style\=\"[^\"]+\")([^>]+)(>)/', '${1}${3}${4}', $content);
        $content = str_ireplace(
            ['<img'],
            ['<amp-img class="contain" layout="responsive"'],
            $content
        );
        $content = preg_replace('/<amp-img(.*?)>/', '<amp-img$1></amp-img>', $content);
        $content = preg_replace('/<iframe\s+.*?\s+src=(".*?").*?<\/iframe>/', '<amp-youtube
			data-videoid=$1
			layout="responsive"
			width="480" height="270"></amp-youtube>', $content);
        $content = str_replace('data-videoid="https://www.youtube.com/embed/', 'data-videoid="', $content);
        return $content;
    }


    /**
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return \StdClass
     */
    public static function curlSendPost($url, $data = array(), $headers = array(), $type_post = null)
    {
        $resource = curl_init();
        curl_setopt($resource, CURLOPT_URL, $url);
        curl_setopt($resource, CURLOPT_POST, true);
        if (!empty($type_post) && $type_post == 'raw') {
            curl_setopt($resource, CURLOPT_POSTFIELDS, $data);
        } else {
            curl_setopt($resource, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($resource, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($resource, CURLOPT_SSL_VERIFYPEER, false);
        if (!empty($headers)) {
            curl_setopt($resource, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($resource, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($resource, CURLOPT_TIMEOUT, 30);

        $result = curl_exec($resource);
        $error = curl_error($resource);

        curl_close($resource);
        return $result;
    }

    /**
     * @param $url
     * @param array $data
     * @param array $header
     * @return mixed
     */
    public static function curlSendGet($url, $data = array(), $header = array())
    {
        $resource = curl_init();
        curl_setopt($resource, CURLOPT_URL, $url . '?' . http_build_query($data));
        curl_setopt($resource, CURLOPT_HTTPGET, true);
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($resource, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($resource, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($resource, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($resource, CURLOPT_TIMEOUT, 120);

        if (!empty($header)) {
            curl_setopt($resource, CURLOPT_HTTPHEADER, $header);
        }

        $result = curl_exec($resource);
        $error = curl_error($resource);
        curl_close($resource);

        if(empty($result)){
            echo json_encode($error);
        }

        return $result;
    }


    public static function curlSendGetIsport($url, $data = array(), $header = array(), $echoUrl=false)
    {
        $resource = curl_init();
        curl_setopt($resource, CURLOPT_URL, $url . '?' . http_build_query($data));
        curl_setopt($resource, CURLOPT_HTTPGET, true);
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($resource, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($resource, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($resource, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($resource, CURLOPT_TIMEOUT, 600);

        if (!empty($header)) {
            curl_setopt($resource, CURLOPT_HTTPHEADER, $header);
        }

        $result = curl_exec($resource);
        $error = curl_error($resource);
        curl_close($resource);

        if(empty($result)){
            echo json_encode($error);
        }
        if($echoUrl) {
            echo PHP_EOL . 'URL REQUEST: '.$url . '?' . http_build_query($data).PHP_EOL;
        }
        return $result;
    }


    public static function saveImageFromUrl($url, $dest)
    {
        $useragents = array(
            'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:74.0) Gecko/20100101 Firefox/74.0',
        );
        $ag = $useragents[rand(0, 1)];
        $ch = curl_init();
        //echo PHP_EOL.$ag;
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $ag);

        $data = curl_exec($ch);
        curl_close($ch);

        file_put_contents($dest, $data);
    }

    static public function convertOdd($handicap)
    {
        $absHan = abs($handicap);
        if ($absHan == 0) {
            return '0:0';
        }
        $value = (int)$absHan;
        $mod = $absHan - $value;
        $strMod = '';
        switch ($mod) {
            case 0.25:
                $strMod = '1/4';
                break;
            case 0.75:
                $strMod = '3/4';
                break;
            case 0.5:
                $strMod = '1/2';
                break;
        }

        $han = ($value == 0) ? $strMod : $value .(!empty($strMod)?' ' . $strMod:'');

        if ($handicap > 0) {
            return '0:' . $han;
        } else {
            return $han . ':0';
        }
    }

    static public function changeOddText($odd)
    {
        $odd = abs($odd);
        $int = (int)$odd;
        $decimal = $odd - $int;
        if ($decimal == 0.25) {
            return ($int) . '-' . ($int + 0.5);
        } else if ($decimal == 0.75) {
            return ($int + 0.5) . '-' . ($int + 1);
        } else {
            return $odd;
        }
    }

    static public function convertOddFractional($handicap)
    {
        $absHan = abs($handicap);
        if ($absHan == 0) {
            return '0';
        }
        $value = (int)$absHan;
        $mod = $absHan - $value;
        $strMod = '';
        switch ($mod) {
            case 0.25:
                $strMod = '1/4';
                break;
            case 0.75:
                $strMod = '3/4';
                break;
            case 0.5:
                $strMod = '1/2';
                break;
        }

        $han = ($value == 0) ? $strMod : $value . ' ' . $strMod;

        return $han;
    }

    public static function getCurrentDate()
    {
        $time = time();
        $hour = (int)date('H');
        if ($hour < 10) {
            $time = strtotime('-1 day');
        }
        $date = date('Y-m-d', $time);
        return $date;
    }

    public static function getTimeQuery($date=null){
        if(empty($date)){
            $date = date('d-m-Y');
        }
        if($date==date('d-m-Y') && date('H') > 10){
            $start_time = strtotime(date($date)) + 12*3600;
            $end_time = $start_time + 2*86400;
            $check_point = $start_time + 86400;
        }else{
            $start_time = strtotime(date($date)) - 12*3600;
            $end_time = $start_time + 2*86400;
            $check_point = $start_time + 86400;
        }
        return [
            'start_time' => $start_time,
            'end_time' => $end_time,
            'check_point' => $check_point,
        ];
    }

    public static function getIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
        return $ip_address;
    }

    static public function getImageFb($image){
        $str = 'http://zq.win007.com';
        if(strpos($image, $str) === 0){
            $image = substr($image, strlen($str));
        }elseif (strpos($image, Yii::$app->params['img_url']['data_url']) === 0){
            return $image;
        }
        //return Yii::$app->params['img_url']['data_url'] . $image;
        return Yii::$app->params['img_url']['data_fb_url'] . $image;
    }

    static public function debugDie($params, $die=true){
        echo '<pre>';
        print_r($params);
        echo '</pre>';
        if($die) die;
    }

    static public function convert_vi_to_en($str) {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", "a", $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", "e", $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", "i", $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", "o", $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", "u", $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", "y", $str);
        $str = preg_replace("/(đ)/", "d", $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", "A", $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", "E", $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", "I", $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", "O", $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", "U", $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", "Y", $str);
        $str = preg_replace("/(Đ)/", "D", $str);
        //$str = str_replace(" ", "-", str_replace("&*#39;","",$str));
        $str = strtolower($str);
        return $str;
    }

    static public function encodeMatchId($matchId){
        return $matchId+109;
    }

    static public function decodeMatchId($matchId){
        return $matchId-109;
    }
}