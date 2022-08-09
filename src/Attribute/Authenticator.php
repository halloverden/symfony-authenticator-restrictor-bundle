<?php

namespace HalloVerden\SymfonyAuthenticatorRestrictorBundle\Attribute;

/**
 * Class Authenticator
 *
 * @package HalloVerden\SymfonyAuthenticatorRestrictorBundle\Attribute
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Authenticator {

  /**
   * Authenticator constructor.
   */
  public function __construct(public array $authenticators) {
  }

}
