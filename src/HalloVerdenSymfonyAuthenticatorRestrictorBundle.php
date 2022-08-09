<?php

namespace HalloVerden\SymfonyAuthenticatorRestrictorBundle;

use HalloVerden\SymfonyAuthenticatorRestrictorBundle\DependencyInjection\Compiler\AddDecoratedAuthenticatorManagerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class HalloVerdenSymfonyAuthenticatorRestrictorBundle extends Bundle {

  /**
   * @inheritDoc
   */
  public function build(ContainerBuilder $container): void {
    parent::build($container);

    $container->addCompilerPass(new AddDecoratedAuthenticatorManagerPass());
  }

}
