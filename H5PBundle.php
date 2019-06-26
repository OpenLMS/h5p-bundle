<?php

namespace OpenLMS\H5PBundle;


use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class H5PBundle
 *
 * @author Hector Prats <hectorpratsortega@gmail.com>
 */
class H5PBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new DependencyInjection\Compiler\AddConsoleCommandPass());
    }
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new DependencyInjection\H5PExtension();
    }
}
