<?php

namespace HalloVerden\SymfonyAuthenticatorRestrictorBundle\DependencyInjection;

use HalloVerden\SymfonyAuthenticatorRestrictorBundle\Attribute\Authenticator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class HalloVerdenSymfonyAuthenticatorRestrictorExtension extends Extension {

  /**
   * @inheritDoc
   * @throws \Exception
   */
  public function load(array $configs, ContainerBuilder $container): void {
    $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
    $loader->load('services.yaml');

    $container->registerAttributeForAutoconfiguration(
      Authenticator::class,
      static function (ChildDefinition $definition, Authenticator $authenticator, \ReflectionClass|\ReflectionMethod $reflector) use ($container) {
        $controller = $reflector instanceof \ReflectionClass ? $reflector->name : $reflector->class . '::' . $reflector->name;
        $container->getDefinition('hallo_verden.authenticator_restrictor.authenticator_attribute_provider')
          ->addMethodCall('addControllerAuthenticators', [$controller, $authenticator->authenticators]);
      }
    );
  }

}
