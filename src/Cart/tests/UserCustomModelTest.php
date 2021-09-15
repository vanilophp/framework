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
use Vanilo\Cart\Models\Cart;
use Vanilo\Cart\Tests\Dummies\Consumer;
use Vanilo\Cart\Tests\Dummies\User;

class UserCustomModelTest extends TestCase
{
    /** @test */
    public function it_returns_the_class_specified_in_auth_providers_users_model_config_by_default()
    {
        $user = factory(User::class)->create();

        $cart = Cart::create([
           'user_id' => $user->id
        ]);

        $this->assertInstanceOf(
            config('auth.providers.users.model'),
            $cart->user
        );
    }

    /** @test */
    public function the_user_model_can_be_specified_in_the_config()
    {
        config(['vanilo.cart.user.model' => Consumer::class]);

        $consumer = factory(Consumer::class)->create();

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
