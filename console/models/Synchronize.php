<?php
/**
 * @Function: Class handler synchronize data form CMC to Vclip
 * @Author: trinh.kethanh@gmail.com
 * @Date: 02/04/2015
 * @System: Video 2.0
 */

namespace console\models;

use common\components\KLogger;
use Yii;
use yii\console\Exception;
use common\components\CFunction;
use console\components\datachecker\ProcessLog;
use console\components\datachecker\DataChecker;
use console\components\datachecker\LockException;
use console\components\datachecker\ProcessManager;
use console\components\datachecker\WorkerProcessManager;
use console\components\datachecker\DataAvailabilityException;

class Synchronize
{

    /**
     * Synchronize data from CMC to Vclip
     */
    public function syncData()
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

        $logger = new ProcessLog([
            'error' => $errorLogFile,
            'warn' => $errorLogFile,
            'info' => $processLogFile,
            'exception' => $errorLogFile
        ]);

        $logger->info('----------------------------------------');
        $logger->info(sprintf('Data checker process gets started'));
        $checker = new DataChecker($logger);
	    $checker->setPIDDir(Yii::$app->getRuntimePath() . '/logs/datachecker/lock');

        try  {
            $workerManager = new WorkerProcessManager(Yii::$app->getRuntimePath() . '/logs/datachecker/lock', CFunction::getParams('max_download'));
            $availableWorkers = $workerManager->getRemaining();
  	 			
            if (0 === $availableWorkers) {
                throw new Exception('Maximum worker process is reached');
             }
            $checker->createPID();
            $workerCount = $workerManager->getCurrentWorkerCount();
            if ($workerCount > 0) {
                if ((CFunction::getParams('max_download') - $workerCount) < CFunction::getParams('data_limit')) {
                    Yii::$app->params['data_limit'] = CFunction::getParams('max_download');
                } else {
                    Yii::$app->params['data_limit'] = $workerCount + CFunction::getParams('data_limit');
                }
            }
            $logger->info('Checking data availability using the web API ' . sprintf(CFunction::getParams('data_url'), CFunction::getParams('site_id'), CFunction::getParams('data_limit')));

            try {
                $hasData = $checker->hasData(sprintf(CFunction::getParams('data_url'), CFunction::getParams('site_id'), CFunction::getParams('data_limit')));
            } catch (Exception $e) {
                throw new DataAvailabilityException($e->getMessage());
            }

            if (true === $hasData) {
                $clips = $checker->getData();
                $logger->info(sprintf('Found %s clips', count($clips)));
                $logger->info('Preparing process manager');

                //$manager = new ProcessManager(CFunction::getParams('php_bin'));
                $manager = new ProcessManager(CFunction::getParams('php_bin').' '.Yii::getAlias('@console').'/');
                $manager->setLoggingMode(true);
                $manager->setLogger($logger);
                $manager->setPayloadPath(Yii::$app->getRuntimePath() . '/logs/datachecker/payload');
                $manager->setPIDFile(Yii::$app->getRuntimePath() . '/logs/datachecker/lock/' . getmypid());

                foreach ($clips as $clip) {
                    $clip['SITE_ID'] = CFunction::getParams('site_id');
                    $key = $clip['ID'];		
                    $clip['STREAM_PRICE'] = 0;
                    $clip['DOWNLOAD_PRICE'] = 0;
                    if (!$manager->hasPayload($key)) {
                        $logger->info('Writing payload for the clip ' . $key);
                        $manager->writePayload($key, $clip);
                        // Run cronjob downloader
                        $manager->addCommand('yii sync/downloader', array($key, Yii::$app->getRuntimePath().'/logs/datachecker/payload', CFunction::getParams('save_dir'), CFunction::getParams('image_save_dir')), CFunction::getParams('timeout'));
                    } else {
                        $logger->info('Payload for the clip ' . $key . ' existed already');
                        $logger->warn('Payload key "' . $key . '" exists');
                    }
                }
                try {
                    $logger->info('Preparing to launch processes');
                    $manager->exec();
                } catch (Exception $e) {
                    $logger->exception($e);
                }
            } else {
                $logger->info('No clip to import');
            }

            $logger->info('Releasing PID file');
            $checker->releasePID();
        } catch (LockException $e) {
            $logger->exception($e);
            $logger->info('Due to file lock issue, data checker will not check the data availability');
            $checker->releasePID();
        } catch (DataAvailabilityException $e) {
            $logger->exception($e);
            $logger->info('Due to data availability issue, no process will be launched');
            $checker->releasePID();
        } catch (Exception $e) {
            $logger->exception($e);
            $checker->releasePID();
        }

        $logger->info('Data checker process exits');
    }

    /**
     * Dong bo phim
     */
    public function syncDataFilm()
    {
        declare(ticks = 1);
        set_time_limit(0);
        date_default_timezone_set('Asia/SaiGon');

      /*  pcntl_signal(SIGALRM, 'handleSignal');
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

        $logger = new ProcessLog([
            'error' => $errorLogFile,
            'warn' => $errorLogFile,
            'info' => $processLogFile,
            'exception' => $errorLogFile
        ]);

        $logger->info('----------------------------------------');
        $logger->info(sprintf('Data checker process gets started'));
        $checker = new DataChecker($logger);
        $checker->setPIDDir(Yii::$app->getRuntimePath() . '/logs/film/lock');

        try  {
            $workerManager = new WorkerProcessManager(Yii::$app->getRuntimePath() . '/logs/film/lock', CFunction::getParams('max_download'));
            $availableWorkers = $workerManager->getRemaining();

            if (0 === $availableWorkers) {
                throw new Exception('Maximum worker process is reached');
            }

            $checker->createPID();
            $workerCount = $workerManager->getCurrentWorkerCount();

            if ($workerCount > 0) {
                if ((CFunction::getParams('max_download') - $workerCount) < CFunction::getParams('data_limit')) {
                    Yii::$app->params['data_limit'] = CFunction::getParams('max_download');
                } else {
                    Yii::$app->params['data_limit'] = $workerCount + CFunction::getParams('data_limit');
                }
            }
            $logger->info('Checking data availability using the web API ' . sprintf(CFunction::getParams('data_url_film'), CFunction::getParams('site_id'), CFunction::getParams('data_limit')));

            try {
                $hasData = $checker->hasData(sprintf(CFunction::getParams('data_url_film'), CFunction::getParams('site_id'), CFunction::getParams('data_limit')));
            } catch (Exception $e) {
                throw new DataAvailabilityException($e->getMessage());
            }

            if (true === $hasData) {
                $films = $checker->getData();

                $logger->info(sprintf('Found %s clips', count($films)));
                $logger->info('Preparing process manager');

                //$manager = new ProcessManager(CFunction::getParams('php_bin'));
                $manager = new ProcessManager(CFunction::getParams('php_bin').' '.Yii::getAlias('@console') . '/');
                $manager->setLoggingMode(true);
                $manager->setLogger($logger);
                $manager->setPayloadPath(Yii::$app->getRuntimePath() . '/logs/film/payload');
                $manager->setPIDFile(Yii::$app->getRuntimePath() . '/logs/film/lock' . getmypid());
                $loggerKLogger = new KLogger('logs'.DS.'MclipServiceCMC_Film'.DS.'Film_'.date('Ymd'), KLogger::INFO);
                $loggerKLogger->LogInfo(date('Y-m-d H:i:s').' BEGIN SYNC FILM===========');
				
                foreach ($films as $film) {
                    $film['site_id'] = CFunction::getParams('site_id');
                    $key = $film['id'];
					 $loggerKLogger->LogInfo(date('Y-m-d H:i:s').'Film ID '.$film['id'] .'- site_id '.$film['site_id']);
                    if (!$manager->hasPayload($key)) {
                        //$logger->info('Writing payload for the clip ' . $key);
                        $manager->writePayload($key, $film);
						$logger->info('Writing payload for the clip ' . $key);
                        $loggerKLogger->LogInfo(date('Y-m-d H:i:s').' Writing payload for the clip ' . $key);

                        // Run cronjob downloader
                       $manager->addCommand('yii sync/downloader-film', array($key, Yii::$app->getRuntimePath() . '/logs/film/payload', CFunction::getParams('save_dir_film'), CFunction::getParams('image_save_dir_film')), CFunction::getParams('timeout'));
                    } else {
						$loggerKLogger->LogInfo('Payload for the clip ' . $key . ' existed already');
                        //$loggerKLogger->LogInfo(date('Y-m-d H:i:s').' Payload for the clip ' . $key . ' existed already');
                        $logger->info('Payload for the clip ' . $key . ' existed already');
                        $logger->warn('Payload key "' . $key . '" exists');
                    }
                }
                $loggerKLogger->LogInfo(date('Y-m-d H:i:s').' END SYNC FILM===========');
                try {
                    $logger->info('Preparing to launch processes');
                    $manager->exec();
                } catch (Exception $e) {
                    $logger->exception($e);
                }
            } else {
                $logger->info('No clip to import');
            }

            $logger->info('Releasing PID file');
            $checker->releasePID();
        } catch (LockException $e) {
            $logger->exception($e);
            $logger->info('Due to file lock issue, data checker will not check the data availability');
            $checker->releasePID();
        } catch (DataAvailabilityException $e) {
            $logger->exception($e);
            $logger->info('Due to data availability issue, no process will be launched');
            $checker->releasePID();
        } catch (Exception $e) {
            $logger->exception($e);
            $checker->releasePID();
        }

        $logger->info('Data checker process exits');
    }

    /**
     * @param $signo
     */
    function sig_handler($signo)
    {
        global $logger;
        global $checker;

        switch ($signo) {
            case SIGTERM:
                $logger->info('Received a SIGTERM');
                $checker->releaseLock();
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
                $checker->releaseLock();
                exit;
                break;

            default:
                $logger->info('Received a ' . $signo);
                $checker->releaseLock();
                exit;
                break;
        }
    }
}
