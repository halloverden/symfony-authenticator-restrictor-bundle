<?php

namespace HalloVerden\SymfonyAuthenticatorRestrictorBundle\DependencyInjection\Compiler;

use HalloVerden\SymfonyAuthenticatorRestrictorBundle\Security\DecoratedAuthenticatorManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class AddDecoratedAuthenticatorManagerPass implements CompilerPassInterface {

  /**
   * @inheritDoc
   */
  public function process(ContainerBuilder $container): void {
    if (!$container->hasParameter('security.firewalls')) {
      return;
    }

    foreach ($container->getParameter('security.firewalls') as $firewallName) {
      if (!$container->hasDefinition('security.authenticator.manager.'.$firewallName)) {
        continue;
      }

      $container->setDefinition(
        'hallo_verden.authenticator_restrictor.authenticator_manager.' . $firewallName,
        new Definition(DecoratedAuthenticatorManager::class, [
          '$authenticatorManager' => new Reference('.inner'),
          '$authenticatorsProvider' => new Reference('hallo_verden.authenticator_restrictor.authenticator_attribute_provider')
        ])
      )->setDecoratedService('security.authenticator.manager.'.$firewallName);
    }
  }

}
