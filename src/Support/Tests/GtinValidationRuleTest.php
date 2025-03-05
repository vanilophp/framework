<?php

declare(strict_types=1);

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Validator;
use PHPUnit\Framework\TestCase;
use Vanilo\Support\Validation\Rules\MustBeAValidGtin;

class GtinValidationRuleTest extends TestCase
{
    private $translator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->translator = new Translator(new ArrayLoader(), 'en_US');
        app()->instance('translator', $this->translator);
    }

    /** @test */
    public function it_accepts_a_valid_gtin()
    {
        $validator = new Validator(
            $this->translator,
            ['gtin' => '1300000000000'],
            ['gtin' => [new MustBeAValidGtin()]]
        );

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_rejects_an_invalid_gtin()
    {
        $validator = new Validator(
            $this->translator,
            ['gtin' => '1300000000001'],
            ['gtin' => [new MustBeAValidGtin()]]
        );

        $this->assertFalse($validator->passes());
    }

    /** @test */
    public function it_rejects_an_array_as_field_value()
    {
        $validator = new Validator(
            $this->translator,
            ['gtin' => []],
            ['gtin' => [new MustBeAValidGtin()]]
        );

        $this->assertFalse($validator->passes());
    }

    /** @test */
    public function it_returns_the_correct_error_message()
    {
        $validator = new Validator(
            $this->translator,
            ['gtin' => 'invalid-gtin'],
            ['gtin' => [new MustBeAValidGtin()]]
        );

        $this->assertEquals(
            'The gtin must be a valid Global Trade Item Number (GTIN) [8, 12, 13 or 14 digits with a valid check digit]',
            $validator->errors()->first('gtin')
        );
    }

    /** @test */
    public function the_validation_message_can_be_overridden()
    {
        $validator = new Validator(
            $this->translator,
            ['gtin' => 'invalid-gtin'],
            ['gtin' => [new MustBeAValidGtin()]],
            ['gtin' => 'Custom GTIN validation error message.']
        );

        $this->assertEquals(
            'Custom GTIN validation error message.',
            $validator->errors()->first('gtin')
        );
    }
}
