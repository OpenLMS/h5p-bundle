<?php

namespace OpenLMS\H5PBundle\Command;



use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Class IncludeAssetsCommand
 *
 * @author Hector Prats <hectorpratsortega@gmail.com>
 */
class IncludeAssetsCommand extends Command
{
    protected static $defaultName = 'h5p-bundle:include-assets';

    /**
     * @var string
     */
    private $kernelProjectDir;
    /**
     * @var FileLocatorInterface
     */
    private $fileLocator;

    public function __construct(string $kernelProjectDir, \Symfony\Component\HttpKernel\Config\FileLocator $fileLocator)
    {
        $this->kernelProjectDir = $kernelProjectDir;
        $this->fileLocator = $fileLocator;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Include the assets from the h5p vendor bundle in the public resources directory of this bundle.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->includeAssets();
    }

    private function includeAssets()
    {
        $fromDir = $this->kernelProjectDir.'/vendor/h5p/';
        $toDir = $this->fileLocator->locate('@H5PBundle/Resources/public/h5p/');

        $coreSubDir = 'h5p-core/';
        $coreDirs = ['fonts', 'images', 'js', 'styles'];
        $this->createSymLinks($fromDir, $toDir, $coreSubDir, $coreDirs);

        $editorSubDir = 'h5p-editor/';
        $editorDirs = ['ckeditor', 'images', 'language', 'libs', 'scripts', 'styles'];
        $this->createSymLinks($fromDir, $toDir, $editorSubDir, $editorDirs);
    }

    private function createSymLinks($fromDir, $toDir, $subDir, $subDirs)
    {
        foreach ($subDirs as $dir) {
            symlink($fromDir . $subDir . $dir, $toDir . $subDir . $dir);
        }
        exit;
    }
}
