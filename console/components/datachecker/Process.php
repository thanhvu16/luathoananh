<?php

/**
 * Single process launcher.
 *
 * @see gwagent repository (Subversion) for more information on process management in PHP
 * @since September 01, 2010
 * @edited April 02, 2015 by thanhtk@vega.com.vn
 */
namespace console\components\datachecker;

use Yii;

class Process
{
    /**
     * Process file pointer.
     * 
     * @var resource
     */
    public $resource;
    
    /**
     *
     * @var array
     */
    public $pipes;

    /**
     * Full process command. E.x: Command e.x: "/sbin/php base_dir/downloader.php file1 site1"
     *
     * @var string
     */
    public $command;

    /**
     * The maximum time that the process is allowed to run.
     *
     * @var int
     */
    public $maxExecutionTime;

    /**
     * Time that the process gets started.
     *
     * @var int
     */
    public $startTime;

    /**
     * Constructs an object of <code>Process</code>
     *
     * @throws ProcessStartException when a process can not start
     * @param string $command Command e.x: "/sbin/php base_dir/downloader.php file1 site1"
     * @param int $maxExecutionTime Maximum execution time
     */
    public function __construct($command, $maxExecutionTime = 0)
    {
        $this->command = $command;
        $this->maxExecutionTime = (int) $maxExecutionTime;

        // Descriptor specification
        $specs = array(
            0 => array('pipe', 'r'),
            1 => array('pipe', 'w'),
            2 => array('pipe', 'w')
        );
        $this->resource = proc_open($this->command, $specs, $this->pipes, null, $_ENV);
	//$this->resource = shell_exec($this->command);
	//var_dump($this->command); die;
        if (false === $this->resource)
        {
            throw new ProcessStartException(sprintf('Unable to launch the process "%s"', $this->command));
        }

        $this->startTime = time();
    }

    /**
     * Gets process ID.
     *
     * @return int
     */
    public function getId()
    {
        $status = proc_get_status($this->resource);
        return $status['pid'];
    }

    /**
     * Is the process running?
     *
     * @return bool
     */
    public function isRunning()
    {
        $status = proc_get_status($this->resource);
        return $status["running"];
    }

    /**
     * Is the time allowed for the process running over?
     *
     * @return bool
     */
    public function isTimeout()
    {
        if ($this->startTime + $this->maxExecutionTime < time())
        {
            return true;
        }

        return false;
    }

    /**
     * Gets start time.
     *
     * @return int
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Gets maximum execution time.
     *
     * @return int
     */
    public function getMaxExecutionTime()
    {
        return $this->maxExecutionTime;
    }

    /**
     * Closes the process.
     */
    public function close()
    {
        fclose($this->pipes[0]);
        fclose($this->pipes[1]);
        fclose($this->pipes[2]);
        proc_close($this->resource);
    }
}
