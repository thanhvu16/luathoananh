<?php
/**
 * @Function: Class handler download data from CMC to Vclip
 * @Author: trinh.kethanh@gmail.com
 * @Date: 02/04/2015
 * @System: Video 2.0
 */
namespace console\models;

use common\components\KLogger;
use Yii;
use yii\console\Exception;
use common\components\Utility;
use common\components\CFunction;
use console\components\datachecker\ProcessLog;
use console\components\datachecker\RemoteFileReader;
use console\components\datachecker\service\MclipServiceCMC;
use console\components\datachecker\RemoteFileReaderException;
use console\components\datachecker\UnexpectedHttpCodeException;

class Downloader
{

    /**
     * @throws Exception
     * Handler download data from CMC to Vclip
     */
    public function downloadData()
    {
        declare(ticks = 1);
        set_time_limit(0);
        date_default_timezone_set('Asia/SaiGon');

        /*pcntl_signal(SIGALRM, 'handleSignal');
        pcntl_signal(SIGTERM, "handleSignal");
        pcntl_signal(SIGHUP,  "handleSignal");
        pcntl_signal(SIGUSR1, "handleSignal");
        pcntl_signal(SIGINT,  "handleSignal");
        pcntl_signal(SIGCONT, "handleSignal");*/

        $errorLogFile = Yii::$app->getRuntimePath() . '/logs/datachecker/log/error';
        $processLogFile = Yii::$app->getRuntimePath() . '/logs/datachecker/log/process';
        ini_set('error_log', $errorLogFile);
        ini_set('process_log', $processLogFile);

        $argv = $_SERVER['argv'];
        $payloadId = $argv[2];
        $payloadPath = $argv[3];
        $saveDir = $argv[4];
        $imageSaveDir = $argv[5];
        $hasPayload = true;
        $localFilePath = null;
        $service = null;

        $logger = new ProcessLog([
            'error' => $errorLogFile,
            'warn' => $errorLogFile,
            'info' => $processLogFile,
            'exception' => $errorLogFile
        ]);
        $this->registerPayload(Yii::$app->getRuntimePath() . '/logs/datachecker/payload', $payloadId);

        try  {
            if (!file_exists($payloadPath . '/' . $payloadId)) {
                $hasPayload = false;
                throw new Exception('Payload file does not exist: ' . $payloadId);
            }

            $payload = file_get_contents($payloadPath . '/' . $payloadId);
            if (false !== $payload) {
                $payload = unserialize($payload);
            }

            $service = new MclipServiceCMC();
            $service->setLogger($logger);
            $rs = $service->process($payload);
            if (false === $rs) {
                throw new Exception('The downloaded file will be deleted because its information can not be updated into the database.');
               
            }
            $clipId = $rs;

            $basePath = pathinfo($payload['DOWNLOAD_URL'], PATHINFO_DIRNAME);
            $fileName = pathinfo($payload["DOWNLOAD_URL"], PATHINFO_FILENAME);
            $ext = pathinfo($payload["DOWNLOAD_URL"], PATHINFO_EXTENSION);

            $logger->info(sprintf('Preparing to download file "%s" associated with the payload %s', $payload["DOWNLOAD_URL"], $payloadId));
            $reader = new RemoteFileReader();
            $encodePath = Utility::storageSolutionEncode($clipId);
            $checkLocalPath = $saveDir . $encodePath . "/" . $clipId . "/" . $clipId . '.mp4';

            $videoFilesExt = array(
                '.3gp', '.mp4', '_level_1.mp4', '_level_2.mp4', '_720p.mp4'
            );

            if (!empty($videoFilesExt) && !empty($clipId)) {
                foreach ($videoFilesExt as $extension) {
                    $localFilePath = $saveDir . $encodePath . "/" . $clipId . "/" . $clipId . $extension;
                    $downloadUrl = $basePath . '/' . $fileName . $extension;
                    $localDir = dirname($localFilePath);
                    if (!is_dir($localDir))
                        mkdir($localDir, 0777, true);
                    exec("chmod -R 777 $localDir");
                   // $logger->info($localFilePath);
                    $logger->info("Download from  " . $downloadUrl . " to ".$localFilePath);
                    $handle = @fopen($downloadUrl, 'r');
                    if ($handle !== false) {
                        $summary = $reader->readAndSave($downloadUrl, $localFilePath, array(
                            'binary' => true,
                            'timeout' => CFunction::getParams('timeout')
                        ));

                        $logger->info(sprintf('File "%s" downloaded - Speed: %s - Byte: %s - Total time: %s', $downloadUrl, $summary->getAverageDownloadSpeed(), $summary->getFileSize(), $summary->getTotalDownloadTime()));
                    } else {
                        $logger->info("File " . $downloadUrl . " is not exist");
                    }
                }
            }

            $imageSaveDir = $imageSaveDir . $encodePath;
            $imgBasePath = pathinfo($payload['THUMBNAIL_URL'], PATHINFO_DIRNAME);
            $imgBaseName = pathinfo($payload['THUMBNAIL_URL'], PATHINFO_FILENAME);
            $imgExt = '.' . pathinfo($payload['THUMBNAIL_URL'], PATHINFO_EXTENSION);
            $imgFileName = $imgBaseName . $imgExt;

            $normalImage = $clipId . $imgExt;
            $largeImage = $clipId . '_large' . $imgExt;
            $arrDownload = array();

            $localFilePath = $imageSaveDir . '/' . $normalImage;
            $downloadUrl = $imgBasePath . '/' . $imgFileName;
            $arrDownload[] = array(
                'local' => $localFilePath,
                'remote' => $downloadUrl
            );

            $localFilePath = $imageSaveDir . '/' . $largeImage;
            $downloadUrl = $imgBasePath . '/' . $imgBaseName . '_large' . $imgExt;
            $arrDownload[] = array(
                'local' => $localFilePath,
                'remote' => $downloadUrl
            );

            for ($i = 0;$i <10;$i++) {
                $localFilePath = $imageSaveDir . '/' . $clipId . '/' . $clipId . "-000". $i . $imgExt;
                $downloadUrl = $imgBasePath . '/' . $imgBaseName . '/' . $imgBaseName . "-000".$i . $imgExt;
                $arrDownload[] = array(
                    'local' => $localFilePath,
                    'remote' => $downloadUrl
                );
            }

            foreach ($arrDownload as $img) {
                $localFilePath = $img['local'];
                $downloadUrl = $img['remote'];
                $localDir = dirname($localFilePath);
                if (!is_dir($localDir))
                    mkdir($localDir, 0777, true);
                exec("chmod -R 777 $localDir");
                $handle = @fopen($downloadUrl,'r');
                if($handle !== false){
                    $summary = $reader->readAndSave($downloadUrl, $localFilePath, array(
                        'binary' => true,
                        'timeout' => CFunction::getParams('timeout')
                    ));

                    $logger->info(sprintf('File "%s" downloaded - Speed: %s - Byte: %s - Total time: %s', $downloadUrl, $summary->getAverageDownloadSpeed(), $summary->getFileSize(), $summary->getTotalDownloadTime()));
                } else {
                    $logger->info("File " . $downloadUrl . " is not exist");
                }
                }

            try {
                if (file_exists($checkLocalPath)) {
                    $reader->read(sprintf(CFunction::getParams('update_status_url'), CFunction::getParams('site_id'), $payload['ID'], 1));
                    $logger->info('Updating the importing status (OK) for the clip ' . $payload['ID'] . ' at site ' . $payload['SITE_ID'] . 'url '.sprintf(CFunction::getParams('update_status_url'), CFunction::getParams('site_id'), $payload['ID'], 1));
                    $service->approve();

                }
            } catch(Exception $e) {
                $logger->info('Removing downloaded clip file because it is unable to update clip status');
                unlink($localFilePath);
            }

            $logger->info('Downloader process exiting');
        } catch (UnexpectedHttpCodeException $e) {
            try {
                $logger->info('Updating the importing status (Unexpected HTTP code) for the clip ' . $payload['ID'] . ' at site ' . $payload['SITE_ID']);
                $reader->read(sprintf(CFunction::getParams('update_status_url'), $payload['SITE_ID'], $payload['ID'], $e->getStatusCode()));
            } catch (Exception $e) {
                $logger->info('Removing downloaded clip file because it is unable to update clip status');
                unlink($localFilePath);
            }
            $logger->info($e->getMessage());
            $logger->exception($e);
        } catch (RemoteFileReaderException $e) {
            $logger->info($e->getMessage());
            $logger->exception($e);
        } catch (Exception $e) {
            $logger->info(sprintf('ERROR ! Downloader process exits because of %s', get_class($e)));
            $logger->exception($e);
            if (null !== $service) {
                if (null !== $service->clipId) {
                    $service->rollback();
                }
            }
            if (null !== $localFilePath) {
                unlink($localFilePath);
            }
        }
        if (true === $hasPayload) {
            unlink($payloadPath . '/' . $payloadId);
        }
    }

    public function downloadDataFilm()
    {
        declare(ticks = 1);
        set_time_limit(0);
        date_default_timezone_set('Asia/SaiGon');

       /* pcntl_signal(SIGALRM, 'handleSignal');
        pcntl_signal(SIGTERM, "handleSignal");
        pcntl_signal(SIGHUP,  "handleSignal");
        pcntl_signal(SIGUSR1, "handleSignal");
        pcntl_signal(SIGINT,  "handleSignal");
        pcntl_signal(SIGCONT, "handleSignal");
*/
        $errorLogFile = Yii::$app->getRuntimePath() . '/logs/film/log/error';
        $processLogFile = Yii::$app->getRuntimePath() . '/logs/film/log/process';
        ini_set('error_log', $errorLogFile);
        ini_set('process_log', $processLogFile);

        $argv = $_SERVER['argv'];
        $payloadId = $argv[2];
        $payloadPath = $argv[3];
        $saveDir = $argv[4];
        $imageSaveDir = $argv[5];
        $hasPayload = true;
        $localFilePath = null;
        $service = null;

        $logger = new ProcessLog([
            'error' => $errorLogFile,
            'warn' => $errorLogFile,
            'info' => $processLogFile,
            'exception' => $errorLogFile
        ]);
        $this->registerPayload(Yii::$app->getRuntimePath() . '/logs/film/payload', $payloadId);
		$logger->info('BEGIN downloadDataFilm funtion ');
        $reader = new RemoteFileReader();
        try  {
			
            $loggerKlog = new KLogger('logs'.DS.'MclipServiceCMC_Film'.DS.'Film_'.date('Ymd'), KLogger::INFO);
            if (!file_exists($payloadPath . '/' . $payloadId)) {
                $hasPayload = false;
				$logger->info('Payload file does not exist '. $payloadId);
                throw new Exception('Payload file does not exist: ' . $payloadId);
            }

            $payload = file_get_contents($payloadPath . '/' . $payloadId);
            if (false !== $payload) {
                $payload = unserialize($payload);
            }
			$logger->info('begin MclipServiceCMC '. $payloadId);
            $service = new MclipServiceCMC();
            $service->setLogger($logger);
            $rs = $service->processFilm($payload);
			
			$logger->info('after processFilm '. $payloadId);
			
            $loggerKlog->LogInfo(date('Y-m-d H:i:s').' RS  ' . json_encode($rs));

            if (false === $rs) {
                $loggerKlog->LogInfo(date('Y-m-d H:i:s').'The downloaded file will be deleted because its information can not be updated into the database.');
                throw new Exception('The downloaded file will be deleted because its information can not be updated into the database.');
            }
            $filmId = $rs;

            $basePath = pathinfo($payload['LARGE_URL'], PATHINFO_DIRNAME);
            $fileName = pathinfo($payload["LARGE_URL"], PATHINFO_FILENAME);
            $ext = pathinfo($payload["LARGE_URL"], PATHINFO_EXTENSION);

            $logger->info(sprintf('Preparing to download file "%s" associated with the payload %s', $payload["LARGE_URL"], $payloadId));
            $encodePath = '';
			$checkLocalPath = $saveDir . $encodePath . "/" . Utility::storageSolutionEncode($filmId) . "/" . $filmId.'_small.jpg';

            $videoFilesExt = array(
                '.jpg'
            );

            if (!empty($videoFilesExt) && !empty($filmId)) {
                $newFileName = 'large.jpg';
                //$localFilePath = $saveDir . $encodePath . "/" . $filmId . "/" . $newFileName;
                $localFilePath = $saveDir . $encodePath . "/" . $filmId. "/large.jpg";
                $downloadUrl = $basePath . '/' . $newFileName;
                $logger->info("Download file  " . $downloadUrl . " to ".$localFilePath);
                $localDir = dirname($localFilePath);
                if (!is_dir($localDir))
                    mkdir($localDir, 0777, true);
                exec("chmod -R 777 $localDir");
                $logger->info($localFilePath);
                $handle = @fopen($downloadUrl,'r');
                if($handle !== false){
                    $summary = $reader->readAndSave($downloadUrl, $localFilePath, array(
                        'binary' => true,
                        'timeout' => CFunction::getParams('timeout')
                    ));

                    $logger->info(sprintf('File "%s" downloaded - Speed: %s - Byte: %s - Total time: %s', $downloadUrl, $summary->getAverageDownloadSpeed(), $summary->getFileSize(), $summary->getTotalDownloadTime()). 'TO : '.$localFilePath);
                } else {
                    $logger->info("File " . $downloadUrl . " is not exist");
                }

                $newFileName = 'small.jpg';
                //$localFilePath = $saveDir . $encodePath . "/" . $filmId . "/" . $newFileName;
                $localFilePath = $saveDir . $encodePath . "/" . $filmId . "/" . 'small.jpg';
                $downloadUrl = $basePath . '/' . $newFileName;
                $logger->info("Download small file  " . $downloadUrl . " to ".$localFilePath . " by " . $basePath);

                $loggerKlog->LogInfo(date('Y-m-d H:i:s').' Download small file  ' . $downloadUrl );
                $loggerKlog->LogInfo(date('Y-m-d H:i:s').' to '.$localFilePath . ' by ' . $basePath);

                $handle = @fopen($downloadUrl,'r');
                if($handle!==false) {
                    $logger->info("Check download $downloadUrl");
                    $summary = $reader->readAndSave($downloadUrl, $localFilePath, array(
                        'binary' => true,
                        'timeout' => CFunction::getParams('timeout')
                    ));

                    $logger->info(sprintf('File "%s" downloaded - Speed: %s - Byte: %s - Total time: %s', $downloadUrl, $summary->getAverageDownloadSpeed(), $summary->getFileSize(), $summary->getTotalDownloadTime()));
                } else {
                    $logger->info("File " . $downloadUrl . " is not exist");
                }
            }
            try {
                //if (file_exists($checkLocalPath)) {
                    $logger->info('Updating the importing status (OK) for the film ' . $payload['id'] . ' at site ' . $payload['site_id']);
                    $reader->read(sprintf(CFunction::getParams('update_status_url_film'), CFunction::getParams('site_id'), $payload['id'], 1));
                    $service->approve();
                //}
            } catch (Exception $e) {
                $logger->info('Removing downloaded film file because it is unable to update film status');
                unlink($localFilePath);
            }

            $logger->info('Downloader process exiting');
        } catch (UnexpectedHttpCodeException $e) {
            try {
                $logger->info('Updating the importing status (Unexpected HTTP code) for the film ' . $payload['id'] . ' at site ' . $payload['site_id']);
                $reader->read(sprintf(CFunction::getParams('update_status_url_film'), $payload['site_id'], $payload['id'], $e->getStatusCode()));
            } catch (Exception $e) {
                $logger->info('Removing downloaded film file because it is unable to update film status');
                unlink($localFilePath);
            }
            $logger->info($e->getMessage());
            $logger->exception($e);
        } catch (RemoteFileReaderException $e) {
            $logger->info($e->getMessage());
            $logger->exception($e);
        } catch (Exception $e) {
            $logger->info(sprintf('ERROR2 Downloader process exits because of %s', get_class($e)));
            $logger->exception($e);
            if (null !== $service) {
                if (null !== $service->clipId) {
                    $service->rollback();
                }
            }
            if (null !== $localFilePath) {
                unlink($localFilePath);
            }
        }
        if (true === $hasPayload) {
            unlink($payloadPath . '/' . $payloadId);
        }
    }

    /**
     * @param $pidDir
     * @param $payloadId
     * @throws Exception
     */
    private function registerPayload($pidDir, $payloadId)
    {
        //$fp = fopen($pidDir . '/' . posix_getppid(), "a");
        $fp = fopen($pidDir . '/' . $payloadId, "a");
        if (false === $fp) {
            throw new Exception('Unable to open master PID file');
        }
        while (!flock($fp, LOCK_EX | LOCK_NB)) {
            usleep(round(rand(0, 100) * 1000));
        }
        fwrite($fp, getmypid() . ':' . $payloadId . "\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    /**
     * @param $signo
     */
    function sig_handler($signo)
    {
        global $logger;
        global $service;

        switch ($signo) {
            case SIGTERM:
                $logger->info('Received a SIGTERM');
                if (null !== $service) {
                    if (null !== $service->clipId)
                    {
                        $service->rollback();
                    }
                }
                exit;
                break;

            case SIGHUP:
                $logger->info('Received a SIGHUP');
                break;

            case SIGUSR1:
                $logger->info('Received a SIGUSR1');
                break;

            case SIGALRM:
                $logger->info('Received a SIGALRM');
                break;

            case SIGINT:
                $logger->info('Received a SIGINT');
                if (null !== $service) {
                    if (null !== $service->clipId) {
                        $service->rollback();
                    }
                }
                exit;
                break;

            default:
                $logger->info('Received a ' . $signo);
                break;
        }
    }
}