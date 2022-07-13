<?php
namespace Sinergi\Gearman\Command;

use Sinergi\Gearman\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Sinergi\Gearman\Process;
use Sinergi\Gearman\Config;
use Sinergi\Gearman\Application as GearmanApplication;

class StartCommand extends Command
{
    /**
     * @var Process
     */
    private $process;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var callable
     */
    private $runtime;

    /**
     * @var GearmanApplication
     */
    private $gearmanApplication;

    /**
     * @var bool
     */
    private $result = false;

    /**
     * @var bool
     */
    private $isDaemon = true;

    protected function configure()
    {
        $this->setName('start')
            ->setDescription('Start the gearman workers daemon')
            ->addOption('bootstrap', null, InputOption::VALUE_OPTIONAL)
            ->addOption('class', null, InputOption::VALUE_OPTIONAL)
            ->addOption('server', null, InputOption::VALUE_OPTIONAL)
            ->addOption('servers', null, InputOption::VALUE_OPTIONAL)
            ->addOption('user', null, InputOption::VALUE_OPTIONAL)
            ->addOption('auto_update', null, InputOption::VALUE_OPTIONAL)
            ->addOption('autoUpdate', null, InputOption::VALUE_OPTIONAL)
            ->addOption('daemon', 'd', InputOption::VALUE_OPTIONAL, '', true);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('Starting gearman-handler: ');

        if (!$this->isDaemon) {
            $isDaemon = $this->isDaemon;
        } elseif ($input->hasOption('daemon')) {
            $isDaemon = $input->getOption('daemon');
            if ($isDaemon == 'false') {
                $isDaemon = false;
            } elseif ($isDaemon == 'true') {
                $isDaemon = true;
            } else {
                $isDaemon = (bool)$isDaemon;
            }
        } else {
            $isDaemon = $this->isDaemon;
        }

        $config = $this->getConfig();
        $config->set($input->getOptions());

        $process = $this->getProcess();
        if ($process->isRunning()) {
            $output->write('[ <error>Failed: Process is already running</error> ]', true);
            return;
        }

        if (is_callable($this->getRuntime())) {
            $runtime = $this->getRuntime();
            $this->setResult(true);
            $output->write('[ <fg=green>OK</fg=green> ]', true);
            $runtime();
        } else {
            $app = $this->getGearmanApplication();
            if (!$app instanceof GearmanApplication) {
                $app = new Application($this->getConfig(), $this->getProcess());
            }
            $this->setResult(true);
            $output->write('[ <fg=green>OK</fg=green> ]', true);
            $app->run($isDaemon);
        }
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        if (null === $this->config) {
            $this->setConfig(new Config);
        }
        return $this->config;
    }

    /**
     * @param Config $config
     * @return $this
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @return Process
     */
    public function getProcess()
    {
        if (null === $this->process) {
            $this->setProcess((new Process($this->getConfig())));
        }
        return $this->process;
    }

    /**
     * @param Process $process
     * @return $this
     */
    public function setProcess(Process $process)
    {
        if (null === $this->getConfig() && $process->getConfig() instanceof Config) {
            $this->setConfig($process->getConfig());
        }
        $this->process = $process;
        return $this;
    }

    /**
     * @return callable
     */
    public function getRuntime()
    {
        return $this->runtime;
    }

    /**
     * @param null|callable $runtime
     * @return $this
     */
    public function setRuntime(callable $runtime = null)
    {
        $this->runtime = $runtime;
        return $this;
    }

    /**
     * @return bool
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param bool $result
     * @return $this
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @return Application
     */
    public function getGearmanApplication()
    {
        return $this->gearmanApplication;
    }

    /**
     * @param Application $gearmanApplication
     * @return $this
     */
    public function setGearmanApplication(Application $gearmanApplication)
    {
        $this->gearmanApplication = $gearmanApplication;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDaemon()
    {
        return $this->isDaemon;
    }

    /**
     * @param bool $isDaemon
     * @return $this
     */
    public function setIsDaemon($isDaemon)
    {
        $this->isDaemon = $isDaemon;
        return $this;
    }
}
