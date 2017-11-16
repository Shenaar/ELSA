<?php

namespace App\Console\Commands;

use App\Exceptions\PhotoNotFoundException;
use App\Service\Photo;
use App\Service\PhotoProcessor\Contracts\PhotoProcessor;
use App\Service\PhotoStorage\Contracts\PhotoStorage;
use App\Service\ResultGenerator\Contracts\ResultGenerator;

use Carbon\Carbon;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;

use Symfony\Component\Console\Input\InputOption;

/**
 * Basic class for photos downloading commands.
 */
abstract class AbstractDownloadCommand extends Command
{
    /**
     * @var PhotoStorage
     */
    protected $photoStorage;

    /**
     * DownloadFullDay constructor.
     *
     * @param PhotoStorage $photoStorage
     */
    public function __construct(PhotoStorage $photoStorage)
    {
        parent::__construct();

        $this->photoStorage = $photoStorage;

        $this->addOption('filename', '', InputOption::VALUE_OPTIONAL, '', null);
        $this->addOption('format', '', InputOption::VALUE_OPTIONAL, '', 'null');
        $this->addOption('processor', 'p', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, '', []);
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $dates = $this->getDatesList();
        $generator = $this->getResultGenerator();

        $bar = $this->output->createProgressBar($dates->count());
        $report = [
            'Success' => 0,
            'Missing' => 0,
            'Retries' => 0,
        ];
        $startTime = microtime(true);

        for ($i = 0; $i < $dates->count(); ) {
            $date = $dates->get($i);

            try {
                $photo = $this->photoStorage->getForDate($date);
                $photo = $this->preProcess($photo);
                ++$report['Success'];

                if ($photo) {
                    $generator->addPhoto($photo);
                }

            } catch (PhotoNotFoundException $exception) {
                $this->output->newLine();
                $this->output->note($exception->getMessage());
                ++$report['Missing'];
            } catch (\Exception $e) {
                $this->output->newLine();
                $this->output->error($e->getMessage());
                ++$report['Retries'];

                continue;
            }

            $bar->advance();
            ++$i;
        }

        $this->output->newLine();
        $this->output->table(array_keys($report), [array_values($report)]);

        $path = storage_path('results/' . ($this->option('filename') ? : $this->getFilename()));
        $this->output->writeln('Storing result as a ' . get_class($generator) . ' in ' . $path);
        $generator->store($path);
        $this->output->success(sprintf('Finished in %.2fs', (microtime(true) - $startTime)));
    }

    /**
     * @return ResultGenerator
     */
    private function getResultGenerator()
    {
        $resultArgument = $this->option('format');

        $resultGenerator = resolve('App\\Service\\ResultGenerator\\' . ucfirst($resultArgument) . 'Result');

        return $resultGenerator;
    }

    /**
     * @param Photo $photo
     *
     * @return Photo
     */
    private function preProcess($photo)
    {
        foreach ($this->option('processor') as $item) {
            /** @var PhotoProcessor $processor */
            $processor = resolve('App\\Service\\PhotoProcessor\\' . ucfirst($item));

            $photo = $processor->process($photo);

            if (is_null($photo)) {
                return null;
            }
        }

        return $photo;
    }

    /**
     * Generates a list of dates to download.
     *
     * @return Collection|Carbon[]
     */
    abstract protected function getDatesList();

    /**
     * Generates a default filename for a command's result.
     *
     * @return string
     */
    abstract protected function getFilename();
}
