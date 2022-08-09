<?php

namespace HalloVerden\SymfonyAuthenticatorRestrictorBundle\Interfaces;

/**
 * Interface NamedAuthenticatorInterface
 *
 * @package HalloVerden\SymfonyAuthenticatorRestrictorBundle\Interfaces
 */
interface NamedAuthenticatorInterface {

  /**
   * @return string
   */
  public function getAuthenticatorName(): string;

}
