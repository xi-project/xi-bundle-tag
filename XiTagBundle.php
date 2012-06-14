<?php

namespace Xi\Bundle\TagBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Xi\Bundle\TagBundle\DependencyInjection\Compiler\TagPass;

class XiTagBundle extends Bundle
{
    
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TagPass());
    }    
}
