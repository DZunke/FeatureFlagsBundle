<?php

namespace DZunke\FeatureFlagsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DZunkeFeatureFlagsBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new Conditions());
    }

}
