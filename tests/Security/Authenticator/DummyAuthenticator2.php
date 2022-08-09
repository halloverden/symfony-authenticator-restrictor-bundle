<?php

namespace HalloVerden\SymfonyAuthenticatorRestrictorBundle\Tests\Security\Authenticator;

use HalloVerden\SymfonyAuthenticatorRestrictorBundle\Interfaces\NamedAuthenticatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;

class DummyAuthenticator2 implements AuthenticatorInterface, NamedAuthenticatorInterface {
  /**
   * @inheritDoc
   */
  public function supports(Request $request): ?bool {
    return true;
  }

  /**
   * @inheritDoc
   */
  public function authenticate(Request $request): void {
  }

  /**
   * @inheritDoc
   */
  public function createAuthenticatedToken(PassportInterface $passport, string $firewallName): TokenInterface {
    throw new AuthenticationException();
  }

  /**
   * @inheritDoc
   */
  public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response {
    return null;
  }

  /**
   * @inheritDoc
   */
  public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response {
    return null;
  }

  /**
   * @inheritDoc
   */
  public function createToken(Passport $passport, string $firewallName): TokenInterface {
    throw new AuthenticationException();
  }

  /**
   * @inheritDoc
   */
  public function getAuthenticatorName(): string {
    return 'dummy2';
  }

}
