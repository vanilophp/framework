<?php

declare(strict_types=1);

/**
 * Contains the UserCustomModelTest class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-10-26
 *
 */

namespace Vanilo\Cart\Tests;

use Illuminate\Database\Schema\Blueprint;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Cart\Models\Cart;
use Vanilo\Cart\Tests\Dummies\Consumer;
use Vanilo\Cart\Tests\Factories\ConsumerFactory;
use Vanilo\Cart\Tests\Factories\UserFactory;

class UserCustomModelTest extends TestCase
{
    #[Test] public function it_returns_the_class_specified_in_auth_providers_users_model_config_by_default()
    {
        $user = UserFactory::new()->create();

        $cart = Cart::create([
           'user_id' => $user->id
        ]);

        $this->assertInstanceOf(
            config('auth.providers.users.model'),
            $cart->user
        );
    }

    #[Test] public function the_user_model_can_be_specified_in_the_config()
    {
        config(['vanilo.cart.user.model' => Consumer::class]);

        $consumer = ConsumerFactory::new()->create();

        $cart = Cart::create([
            'user_id' => $consumer->id
        ]);

        $this->assertInstanceOf(Consumer::class, $cart->user);
    }

    protected function setUpDatabase($app)
    {
        parent::setUpDatabase($app);

        $app['db']->connection()->getSchemaBuilder()->create('consumers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }
}
