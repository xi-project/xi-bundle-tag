<?php

namespace Xi\Bundle\TagBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * 
 *
 * @author Henri Vesala <henri.vesala@gmail.com>
 */
class TagPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
       
        if (false === $container->hasDefinition('xi_tag.service.tag')) {
            return;
        }

        $definition = $container->getDefinition('xi_tag.service.tag');
        foreach ($container->findTaggedServiceIds('tag.loader') as $id => $attributes) {
            $definition->addMethodCall('addServiceReference', array(new Reference($id)));
        }
    }
}
