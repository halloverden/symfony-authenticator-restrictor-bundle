<?php

namespace HalloVerden\SymfonyAuthenticatorRestrictorBundle\Tests\Security;

use HalloVerden\SymfonyAuthenticatorRestrictorBundle\Provider\AuthenticatorsProvider;
use HalloVerden\SymfonyAuthenticatorRestrictorBundle\Security\DecoratedAuthenticatorManager;
use HalloVerden\SymfonyAuthenticatorRestrictorBundle\Tests\Security\Authenticator\DummyAuthenticator1;
use HalloVerden\SymfonyAuthenticatorRestrictorBundle\Tests\Security\Authenticator\DummyAuthenticator2;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticatorManager;

class DecoratedAuthenticatorManagerTest extends TestCase {

  public function testSupports_restrictedRoute_shouldRemoveUnsupportedAuthenticators() {
    $authenticatorManager = $this->createMock(AuthenticatorManager::class);
    $authenticatorManager->method('supports')->willReturn(true);

    $authenticatorsProvider = $this->createMock(AuthenticatorsProvider::class);
    $authenticatorsProvider->method('getAuthenticators')->willReturn(['dummy1']);

    $request = new Request();
    $request->attributes->set('_controller', 'test_controller');
    $request->attributes->set('_security_skipped_authenticators', []);
    $request->attributes->set('_security_authenticators', [new DummyAuthenticator1(), new DummyAuthenticator2()]);

    $decoratedAuthenticatorManager = new DecoratedAuthenticatorManager($authenticatorManager, $authenticatorsProvider);

    $supports = $decoratedAuthenticatorManager->supports($request);
    $this->assertTrue($supports);

    $authenticators = $request->attributes->get('_security_authenticators');
    $this->assertCount(1, $authenticators);
    $this->assertInstanceOf(DummyAuthenticator1::class, $authenticators[0]);
  }

  public function testSupports_unrestrictedRoute_shouldNotRemoveAuthenticators() {
    $authenticatorManager = $this->createMock(AuthenticatorManager::class);
    $authenticatorManager->method('supports')->willReturn(true);

    $authenticatorsProvider = $this->createMock(AuthenticatorsProvider::class);
    $authenticatorsProvider->method('getAuthenticators')->willReturn(null);

    $request = new Request();
    $request->attributes->set('_controller', 'test_controller');
    $request->attributes->set('_security_skipped_authenticators', []);
    $request->attributes->set('_security_authenticators', [new DummyAuthenticator1(), new DummyAuthenticator2()]);

    $decoratedAuthenticatorManager = new DecoratedAuthenticatorManager($authenticatorManager, $authenticatorsProvider);

    $supports = $decoratedAuthenticatorManager->supports($request);
    $this->assertTrue($supports);

    $authenticators = $request->attributes->get('_security_authenticators');
    $this->assertCount(2, $authenticators);
    $this->assertInstanceOf(DummyAuthenticator1::class, $authenticators[0]);
    $this->assertInstanceOf(DummyAuthenticator2::class, $authenticators[1]);
  }

}
