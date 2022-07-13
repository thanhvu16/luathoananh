<?php
/**
 * The class provides service to check if there is any clip that needs to be imported into the system.
 * Under the hood, a background process will be launched, a HTTP API will be called to clip.vn and fetch
 * a JSON output.
 *
 * @since September 01, 2010
 * @version $Id: datachecker.php 8389 2010-09-23 18:13:52Z pcdinh $
 */
namespace console\components\datachecker;

class DataChecker
{
    /**
     * JSON data.
     *
     * @var string
     */
    public $data;

    /**
     * Process message logger.
     *
     * @var ProcessLog
     */
    public $logger;

    /**
     * Lock file directory.
     *
     * @var string
     */
    public $pidDir;

    /**
     * Absolute path to created PID file.
     *
     * @var string
     */
    public $pidFile;

    /**
     * Constructs an object of <code>DataChecker</code>.
     *
     * @param ProcessLog $logger
     */
    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    /**
     * Fetches a JSON output from remote HTTP API (clip.vn)
     *
     * @param string $url
     * @return array|false
     */
    public function retrieve($url)
    {
        $reader = new RemoteFileReader();
        return $reader->read($url);
    }

    /**
     * Fetches a JSON output from remote HTTP API (clip.vn)
     *
     * @param string $url
     * @return bool
     */
    public function hasData($url)
    {
        $data = $this->retrieve($url);

        if ('[]' === $data || empty($data))
        {
            return false;
        }

        $this->data = $data;
        return true;
    }

    /**
     * Gets remote content data.
     *
     * @return array
     */
    public function getData()
    {
        if (!empty($this->data))
        {
            return json_decode($this->data, true);
        }

        return array();
    }

    /**
     * Sets lock directory.
     *
     * @param string $path
     */
    public function setPIDDir($path)
    {
        $this->pidDir = $path;
    }

    /**
     * Acquires a lock file.
     *
     * @throws LockException
     * @return bool
     */
    public function acquireLock()
    {
        $this->logger->info('Acquiring lock file');

        if (file_exists($this->pidDir . '/datachecker.lock'))
        {
            throw new AlreadyLockedException('Another proccess already got the lock file: ' . $this->pidDir . '/datachecker.lock');
        }

        $fp = fopen($this->pidDir . '/datachecker.lock', "w+");

        if (false === $fp)
        {
            throw new LockException('Unable to create a lock file');
        }

        fwrite($fp, getmypid());
        fclose($fp);
    }

    /**
     * Releases lock file.
     */
    public function releaseLock()
    {
        unlink($this->pidDir . '/datachecker.lock');
    }

    /**
     * Creates PID file
     */
    public function createPID()
    {
        $pidFile = $this->pidDir . '/' . getmypid();
        $fp = fopen($pidFile, "w+");

        if (false === $fp)
        {
            throw new LockException('Unable to create a lock file');
        }

        $this->pidFile = $pidFile;
        fclose($fp);
    }

    /**
     * Gets absolute path to the created PID file.
     *
     * @return string null returns when no PID is created
     */
    public function getPIDFile()
    {
        return $this->pidFile;
    }

    /**
     * Releases current process PID file
     */
    public function releasePID()
    {
        if (file_exists($this->pidFile))
        {
            unlink($this->pidFile);
        }
    }
}
