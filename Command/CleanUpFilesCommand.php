<?php

namespace OpenLMS\H5PBundle\Command;


use OpenLMS\H5PBundle\Core\H5POptions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CleanUpFilesCommand
 *
 * @author Hector Prats <hectorpratsortega@gmail.com>
 */
class CleanUpFilesCommand extends Command
{
    protected static $defaultName = 'h5p-bundle:cleanup-files';
    /**
     * @var H5POptions
     */
    private $H5POptions;

    public function __construct(H5POptions $H5POptions)
    {
        $this->H5POptions = $H5POptions;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->addArgument('location', InputArgument::OPTIONAL, 'The location of the files to clean up.')
            ->setDescription('Include the assets from the h5p vendor bundle in the public resources directory of this bundle.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->cleanupFiles($input);
    }

    private function cleanupFiles(InputInterface $input)
    {
        $location = $input->getArgument('location');
        if (!$location) {
            $location = $this->H5POptions->getAbsoluteH5PPath() . '/editor';
        }

        \H5PCore::deleteFileTree($location);
    }
}
