<?php


namespace OpenLMS\H5PBundle\DependencyInjection\Compiler;


use OpenLMS\H5PBundle\Command\CleanUpFilesCommand;
use OpenLMS\H5PBundle\Command\IncludeAssetsCommand;
use Symfony\Component\DependencyInjection;
use Symfony\Component\Console\ConsoleEvents;



/**
 * class AddConsoleCommandPass
 *
 * @author Hector Prats <hectorpratsortega@gmail.com> h5p-bundle-develop
 */
class AddConsoleCommandPass implements DependencyInjection\Compiler\CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(DependencyInjection\ContainerBuilder $container)
    {
        $definition = new DependencyInjection\Definition(CleanUpFilesCommand::class);
        $definition->setPublic(true);
        $definition->addTag(ConsoleEvents::COMMAND);
        $definition->setAutowired(true);
        $container->setDefinition(CleanUpFilesCommand::class, $definition);

        $definition = new DependencyInjection\Definition(IncludeAssetsCommand::class);
        $definition->setPublic(true);
        $definition->addTag(ConsoleEvents::COMMAND);
        $definition->setAutowired(true);
        $definition->setArgument('$kernelProjectDir', '%kernel.project_dir%');
        $container->setDefinition(IncludeAssetsCommand::class, $definition);
    }
}
