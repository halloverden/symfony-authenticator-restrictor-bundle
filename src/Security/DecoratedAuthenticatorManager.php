<?php

namespace HalloVerden\SymfonyAuthenticatorRestrictorBundle\Security;

use HalloVerden\SymfonyAuthenticatorRestrictorBundle\Interfaces\NamedAuthenticatorInterface;
use HalloVerden\SymfonyAuthenticatorRestrictorBundle\Provider\AuthenticatorsProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticatorManagerInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;

/**
 * Class AuthenticatorManagerDecorator
 *
 * @package HalloVerden\SymfonyAuthenticatorRestrictorBundle\Security
 * @internal
 */
class DecoratedAuthenticatorManager implements AuthenticatorManagerInterface, UserAuthenticatorInterface {

  /**
   * AuthenticatorManagerDecorator constructor.
   */
  public function __construct(
    private readonly AuthenticatorManagerInterface&UserAuthenticatorInterface $authenticatorManager,
    private readonly AuthenticatorsProvider $authenticatorsProvider
  ) {
  }

  /**
   * @inheritDoc
   */
  public function supports(Request $request): ?bool {
    $supports = $this->authenticatorManager->supports($request);
    if (false === $supports) {
      return false;
    }

    $supportedAuthenticators = $this->authenticatorsProvider->getAuthenticators($request->attributes->get('_controller'));
    if (null === $supportedAuthenticators) {
      return $supports;
    }

    $authenticators = [];
    $skippedAuthenticators = $request->attributes->get('_security_skipped_authenticators');
    foreach ($request->attributes->get('_security_authenticators') as $authenticator) {
      if ($this->isSupportedAuthenticator($authenticator, $supportedAuthenticators)) {
        $authenticators[] = $authenticator;
      } else {
        $skippedAuthenticators[] = $authenticator;
      }
    }

    if (empty($authenticators)) {
      $request->attributes->remove('_security_authenticators');
      $request->attributes->remove('_security_skipped_authenticators');
      return false;
    }

    $request->attributes->set('_security_authenticators', $authenticators);
    $request->attributes->set('_security_skipped_authenticators', $skippedAuthenticators);

    return $supports;
  }

  /**
   * @inheritDoc
   */
  public function authenticateRequest(Request $request): ?Response {
    return $this->authenticatorManager->authenticateRequest($request);
  }

  /**
   * @inheritDoc
   */
  public function authenticateUser(UserInterface $user, AuthenticatorInterface $authenticator, Request $request, array $badges = []): ?Response {
    return $this->authenticatorManager->authenticateUser($user, $authenticator, $request, $badges);
  }

  /**
   * @param AuthenticatorInterface $authenticator
   * @param array                  $supportedAuthenticators
   *
   * @return bool
   */
  private function isSupportedAuthenticator(AuthenticatorInterface $authenticator, array $supportedAuthenticators): bool {
    if ($authenticator instanceof NamedAuthenticatorInterface) {
      return \in_array($supportedAuthenticators, $authenticator->getAuthenticatorName(), true);
    }

    foreach ($supportedAuthenticators as $supportedAuthenticator) {
      if ($authenticator instanceof $supportedAuthenticator) {
        return true;
      }
    }

    return false;
  }

}
