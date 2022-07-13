<?php

/**
 * Manages process launching.
 *
 * @since September 01, 2010
 * @edited April 02, 2015 by thanhtk@vega.com.vn
 * @version $Id: ProcessManager.php 8543 2010-09-29 09:40:55Z pcdinh $
 */
namespace console\components\datachecker;

class ProcessManager
{
    /**
     * Script or shell compiler/executor such as PHP.
     *
     * @var string
     */
    public $executable;

    /**
     * Scripts to run.
     *
     * @var array
     */
    public $scripts = array();

    /**
     * Number of processes that are running.
     *
     * @var int
     */
    public $processesRunning = 0;

    /**
     * An array of process descriptors
     *
     * @var array
     */
    public $running = array();

    /**
     * Time before checking child processes' status.
     *
     * @var int
     */
    public $sleepTime = 2;

    /**
     * Logging mode.
     *
     * @var string
     */
    public $loggingMode = false;

    /**
     * Logger.
     *
     * @var ProcessLog
     */
    public $logger;

    /**
     * Payload path.
     *
     * @var string
     */
    public $payloadPath;

    /**
     * PID file path.
     *
     * @var string
     */
    public $pidFile;

    /**
     * Constructs an object of <code>ProcessManager</code>
     *
     * @param string $executable Script or shell compiler/executor such as PHP
     */
    public function __construct($executable)
    {
        $this->executable = $executable;
    }

    /**
     * Sets payload path.
     *
     * @param string $path
     */
    public function setPayloadPath($path)
    {
        $this->payloadPath = $path;
    }

    /**
     * Sets PID file path.
     *
     * @param string $path
     */
    public function setPIDFile($path)
    {
        $this->pidFile = $path;
    }

    /**
     * Adds a command to launch a process.
     *
     * @param array $args Script arguments
     * @param int $maxExecutionTime
     */
    public function addCommand($command, $args = array(), $maxExecutionTime = 300)
    {
        if (!empty($args))
        {
            $command = $command . ' ' . implode(' ', $args);

        }

        $this->scripts[] = array("command" => $command, "max_execution_time" => $maxExecutionTime);
    }

    /**
     * Executes commands
     *
     * @throws \InvalidArgumentException
     */
    public function exec()
    {
        $i = 0;

        if (true === $this->loggingMode && null === $this->logger)
        {
            throw new \InvalidArgumentException('Logging mode is turned on but logger is not set yet. See #setLogger()');
        }

        if (!empty($this->executable))
        {
            //$executable = $this->executable . ' ';
            $executable = $this->executable;
        }

        $max = count($this->scripts);
        $launched = 0;

        while (true)
        {
            // Some scripts are not launched
            if ($launched < $max)
            {
                $this->logger->info(sprintf('Trying to launched %s process', $max - $launched));

                // Fill up the slots
                for ($i = 0; $i < $max; $i++)
                {
                    if (!isset($this->scripts[$i]))
                    {
                        continue;
                    }

                    // ob_flush();
                    flush();
                    $this->logger->info(sprintf('Process to be launched "%s"', $executable . $this->scripts[$i]["command"]));

                    try
                    {
                        $this->running[] = new Process($executable . $this->scripts[$i]["command"], $this->scripts[$i]["max_execution_time"]);
                        unset($this->scripts[$i]);
                        $this->processesRunning++;
                        $launched++;
                    }
                    catch (ProcessStartException $e)
                    {
                        $this->logger->warn($e->getMessage());
                    }
                }
            }

            // Check if done
            if (($this->processesRunning == 0) && ($i >= count($this->scripts)))
            {
                break;
            }

            // sleep, this duration depends on your script execution time, the longer execution time, the longer sleep time
            sleep($this->sleepTime);

            // check what is done
            foreach ($this->running as $key => $process)
            {
                /* @var $process Process */
                if (false === $process->isRunning() || true === $process->isTimeout())
                {
                    if (true === $this->loggingMode)
                    {
                        if (false === $process->isRunning())
                        {
                            $this->logger->info('Completed: Process "' . $process->command . '"');
                        }
                        elseif (true === $process->isTimeout())
                        {
                            $this->logger->info('Timeout: Process "' . $process->command . '"');
                        }
                    }

                    $pid = $process->getId(); // Process ID of the closing child process
                    $this->logger->info(sprintf('Closing process %s: "' . $process->command . '"', $pid));
                    $process->close();
                    unset($this->running[$key]);
                    $this->processesRunning--;
                    $this->unregisterChildProcess($pid);
                    // ob_flush();
                    flush();
                }
            }
        }
    }

    /**
     * Sets logging mode.
     *
     * @param bool $mode
     */
    public function setLoggingMode($mode)
    {
        $this->loggingMode = (bool) $mode;
    }

    /**
     * Sets logger.
     *
     * @param ProcessLog $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * Gets prepared payloads.
     *
     * @return array
     */
    public function getPayloads()
    {
        $iter = new \DirectoryIterator($this->payloadPath);
        $clipIds = array();

        /* @var $file \DirectoryIterator */
        foreach ($iter as $file)
        {
            if ($file->isDot())
            {
                continue;
            }

            // Path to master process PID (datachecker.php)
            $clipIds[] = $file->getFilename();
        }

        return $clipIds;
    }

    /**
     * Checks if a process payload exists.
     *
     * @param string $key Payload key
     * @return bool
     */
    public function hasPayload($key)
    {
        if (!file_exists($this->payloadPath . '/' . $key))
        {
            return false;
        }

        return true;
    }

    /**
     * Writes process payload into file.
     *
     * @param string $key Payload file name
     * @param array $data Process payload
     */
    public function writePayload($key, $data)
    {
        $data['__ts__'] = time(); // timestamp to ensure that payload can be expired
        file_put_contents($this->payloadPath . '/' . $key, serialize($data));
    }

    /**
     * Removes child process record from the master PID file.
     *
     * @param int $pid Child process ID
     */
    public function unregisterChildProcess($pid)
    {
        if (!file_exists($this->pidFile))
        {
            $this->logger->warn('PID file does not exist "' . $this->pidFile . '"');
            return;
        }

        // do an exclusive lock
        $lines = file($this->pidFile);

        for ($i = 0, $len = count($lines); $i < $len; $i++)
        {
            $line = trim($lines[$i]); // remove traling \n

            if (empty($line))
            {
                continue;
            }

            if (0 === strpos($line, $pid))
            {
                unset($lines[$i]);

                $parts = explode(':', $line);

                if (file_exists($this->payloadPath . '/' . $parts[1]))
                {
                    unlink($this->payloadPath . '/' . $parts[1]);
                }
            }
        }

        file_put_contents($this->pidFile, implode("", $lines));
    }
}
