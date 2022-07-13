<?php

/**
 * A class that is designed to ensure that launched workers are not greater than the set number
 *
 * @since September 22, 2010
 * @edited April 02, 2015 by thanhtk@vega.com.vn
 */
namespace console\components\datachecker;

class WorkerProcessManager
{
    /**
     * Maximum download process that can be launched.
     *
     * @var int
     */
    public $max = 20;

    /**
     * Full path to the directory that holds PID files.
     *
     * @var string
     */
    public $pidDirectory;

    /**
     * Constructs an object of <code>WorkerProcessManager</code>.
     *
     * @param string $dir PID directory path
     * @param int $max Maximum downloader process
     */
    public function  __construct($dir, $max)
    {
        $this->pidDirectory = $dir;
        $this->max = $max;
    }

    /**
     * Gets master process PIDs.
     *
     * @throws \Exception
     * @return array
     */
    public function getCurrentMasterPID()
    {
        $iter = new \DirectoryIterator($this->pidDirectory);
        $pids = array();

        /* @var $file \DirectoryIterator */
        foreach ($iter as $file)
        {
            if ($file->isDot())
            {
                continue;
            }

            // Path to master process PID (datachecker.php)
            $pids[] = $file->getFilename();
        }

        return $pids;
    }

    /**
     * Counts launched master processes.
     *
     * @return int
     */
    public function getMasterCount()
    {
        $iter = new \DirectoryIterator($this->pidDirectory);
        $count = 0;

        /* @var $file \DirectoryIterator */
        foreach ($iter as $file)
        {
            if ($file->isDot())
            {
                continue;
            }

            $count++;
        }

        return $count;
    }

    /**
     * Gets the number of launched workers.
     *
     * @throws \Exception
     * @return int
     */
    public function getCurrentWorkerCount()
    {
        $iter = new \DirectoryIterator($this->pidDirectory);
        $count = 0;

        /* @var $file \DirectoryIterator */
        foreach ($iter as $file)
        {
            if ($file->isDot())
            {
                continue;
            }

            // Path to master process PID files (datachecker.php)
            $path = $file->getPathname();
            $count += count(file($path));
        }

        return $count;
    }

    /**
     * Gets the numbers of workers that can be launched.
     *
     * @throws \Exception
     * @return int
     */
    public function getRemaining()
    {
        return $this->max - $this->getCurrentWorkerCount();
    }
}