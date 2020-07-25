<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;
use Symfony\Contracts\Cache\CacheInterface;

class StepInfoCommand extends Command
{
    protected static $defaultName = 'app:step:info';
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * StepInfoCommand constructor.
     *
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        parent::__construct();

        $this->cache = $cache;
    }

    protected function configure()
    {
        $this->setDescription('Command for checking caching.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $step = $this->cache->get('app.current_step', function ($item) {
            $process = new Process(['whoami']);
            $process->mustRun();
            $item->expiresAfter(30);

            return $process->getOutput();
        });

        $io->success($step);

        return 0;
    }
}
