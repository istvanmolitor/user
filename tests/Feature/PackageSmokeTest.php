<?php

namespace Molitor\User\Tests\Feature;

use Molitor\User\Providers\UserServiceProvider;
use Tests\TestCase;

class PackageSmokeTest extends TestCase
{
    public function test_service_provider_is_loaded(): void
    {
        $this->assertTrue(class_exists(UserServiceProvider::class));
        $this->assertTrue($this->app->providerIsLoaded(UserServiceProvider::class));
    }
}

