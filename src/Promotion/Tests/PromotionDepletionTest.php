<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Promotion\Models\Promotion;

class PromotionDepletionTest extends TestCase
{
    #[Test] public function it_is_not_depleted_if_the_usage_limit_is_null()
    {
        $this->assertFalse((new Promotion())->isDepleted());
    }

    #[Test] public function it_is_depleted_if_the_usage_limit_is_zero()
    {
        $this->assertTrue((new Promotion(['usage_limit' => 0]))->isDepleted());
    }

    #[Test] public function it_is_depleted_if_the_usage_limit_equals_the_usage_count()
    {
        $this->assertTrue((new Promotion(['usage_limit' => 1, 'usage_count' => 1]))->isDepleted());
    }

    #[Test] public function it_is_depleted_if_the_usage_limit_is_smaller_than_the_usage_count()
    {
        $this->assertTrue((new Promotion(['usage_limit' => 1, 'usage_count' => 2]))->isDepleted());
    }

    #[Test] public function it_is_not_depleted_if_the_usage_limit_is_greater_than_the_usage_count()
    {
        $this->assertFalse((new Promotion(['usage_limit' => 3, 'usage_count' => 2]))->isDepleted());
    }
}
