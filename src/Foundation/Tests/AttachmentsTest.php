<?php

declare(strict_types=1);

/**
 * Contains the AttachmentsTest class.
 *
 * @copyright   Copyright (c) 2026 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2026-10-28
 *
 */

namespace Vanilo\Foundation\Tests;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Vanilo\Foundation\Models\MasterProduct;
use Vanilo\Foundation\Models\MasterProductVariant;
use Vanilo\Foundation\Models\Product;

class AttachmentsTest extends TestCase
{
    protected const TEST_IMAGE = __DIR__ . '/img/vanilo_640.png';

    #[Test] #[DataProvider('models')] public function a_file_can_be_attached(string $class)
    {
        $model = $this->createModel($class);
        $this->assertFalse($model->hasAttachments());

        $model->addAttachment(UploadedFile::fake()->create('manual.pdf', 120, 'application/pdf'));

        $model = $model->fresh();
        $this->assertTrue($model->hasAttachments());
        $this->assertEquals(1, $model->attachmentCount());
        $this->assertEquals('manual.pdf', $model->getAttachments()->first()->file_name);
    }

    #[Test] #[DataProvider('models')] public function attachments_are_separated_from_images(string $class)
    {
        $model = $this->createModel($class);
        $model->addMedia(self::TEST_IMAGE)->preservingOriginal()->toMediaCollection();
        $model->addAttachment(UploadedFile::fake()->create('specs.xlsx', 12));
        $model->addAttachment(UploadedFile::fake()->create('warranty.txt', 1));

        $model = $model->fresh();
        $this->assertEquals(1, $model->imageCount());
        $this->assertEquals(2, $model->attachmentCount());
        $this->assertEquals(['specs.xlsx', 'warranty.txt'], $model->getAttachments()->pluck('file_name')->all());
    }

    #[Test] public function image_conversions_are_not_generated_for_attachments()
    {
        $product = $this->createModel(Product::class);
        $image = $product->addMedia(self::TEST_IMAGE)->preservingOriginal()->toMediaCollection();
        $attachment = $product->addAttachment($this->copyOfTestImage());

        $this->assertTrue($image->hasGeneratedConversion('thumbnail'));
        $this->assertFalse($attachment->hasGeneratedConversion('thumbnail'));
    }

    #[Test] public function an_attachment_can_be_given_a_custom_name()
    {
        $product = $this->createModel(Product::class);
        $attachment = $product->addAttachment(UploadedFile::fake()->create('ug-v3.pdf', 5), 'User Guide');

        $this->assertEquals('User Guide', $attachment->name);
        $this->assertEquals('ug-v3.pdf', $attachment->file_name);
    }

    #[Test] public function an_attachment_can_be_retrieved_by_id_or_uuid()
    {
        $product = $this->createModel(Product::class);
        $image = $product->addMedia(self::TEST_IMAGE)->preservingOriginal()->toMediaCollection();
        $attachment = $product->addAttachment(UploadedFile::fake()->create('manual.pdf', 5));

        $product = $product->fresh();
        $this->assertEquals($attachment->id, $product->getAttachment($attachment->id)?->id);
        $this->assertEquals($attachment->id, $product->getAttachment($attachment->uuid)?->id);
        $this->assertNull($product->getAttachment($image->id));
        $this->assertNull($product->getAttachment(PHP_INT_MAX));
    }

    #[Test] public function an_attachment_can_be_downloaded()
    {
        $product = $this->createModel(Product::class);
        $product->addAttachment(UploadedFile::fake()->createWithContent('readme.txt', 'Hello Vanilo'));

        $attachment = $product->fresh()->getAttachments()->first();
        $response = $attachment->toResponse(Request::create('/'));

        $this->assertInstanceOf(StreamedResponse::class, $response);
        $this->assertStringContainsString('attachment; filename="readme.txt"', $response->headers->get('Content-Disposition'));
        $this->assertEquals('Hello Vanilo', file_get_contents($attachment->getPath()));
    }

    public static function models(): array
    {
        return [
            'Product' => [Product::class],
            'Master Product' => [MasterProduct::class],
            'Master Product Variant' => [MasterProductVariant::class],
        ];
    }

    private function createModel(string $class): HasMedia
    {
        static $counter = 0;
        $counter++;

        return match ($class) {
            Product::class => Product::create(['name' => "Product $counter", 'sku' => "P-$counter"]),
            MasterProduct::class => MasterProduct::create(['name' => "Master $counter"]),
            MasterProductVariant::class => MasterProductVariant::create([
                'master_product_id' => MasterProduct::create(['name' => "Master $counter"])->id,
                'sku' => "MPV-$counter",
            ]),
        };
    }

    private function copyOfTestImage(): string
    {
        $path = sys_get_temp_dir() . '/' . uniqid('vanilo_attachment_') . '.png';
        copy(self::TEST_IMAGE, $path);

        return $path;
    }
}
