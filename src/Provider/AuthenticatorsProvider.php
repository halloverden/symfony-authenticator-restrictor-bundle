<?php

namespace HalloVerden\SymfonyAuthenticatorRestrictorBundle\Provider;

use HalloVerden\SymfonyAuthenticatorRestrictorBundle\Attribute\Authenticator;

/**
 * Class AuthenticatorAttributeProvider
 *
 * @package HalloVerden\SymfonyAuthenticatorRestrictorBundle\Services
 * @internal
 */
class AuthenticatorsProvider {
  private array $controllerAuthenticatorsMap = [];

  /**
   * @param string $controller
   *
   * @return Authenticator|null
   */
  public function getAuthenticators(string $controller): ?array {
    return $this->controllerAuthenticatorsMap[$controller] ?? null;
  }

  /**
   * @param string $controller
   * @param array  $authenticators
   *
   * @return void
   */
  public function addControllerAuthenticators(string $controller, array $authenticators): void {
    $this->controllerAuthenticatorsMap[$controller] = $authenticators;
  }

}
