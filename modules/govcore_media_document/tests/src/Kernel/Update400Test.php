<?php

namespace Drupal\Tests\govcore_media_document\Kernel;

use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\KernelTests\KernelTestBase;
use Drupal\govcore_media_document\Update\Update400;
use Drupal\Tests\media\Traits\MediaTypeCreationTrait;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * Tests configuration updates targeting GovCore Media Document 4.0.0.
 *
 * @group govcore_media
 * @group govcore_media_document
 *
 * @coversDefaultClass \Drupal\govcore_media_document\Update\Update400
 */
class Update400Test extends KernelTestBase {

  use MediaTypeCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'field',
    'file',
    'govcore_media_document',
    'media',
  ];

  /**
   * Tests making the Document media type's source field required.
   *
   * @covers ::requireDocumentMediaSourceField
   */
  public function testRequireDocumentMediaSourceField() {
    $this->createMediaType('file', [
      'id' => 'document',
      'label' => 'Document',
    ]);

    $field_storage = FieldStorageConfig::create([
      'field_name' => 'field_document',
      'type' => 'string',
      'entity_type' => 'media',
    ]);
    $field_storage->save();

    $field = FieldConfig::create([
      'field_storage' => $field_storage,
      'bundle' => 'document',
      'label' => 'Document',
    ]);
    $field->save();
    $this->assertFalse($field->isRequired());

    $io = $this->prophesize(StyleInterface::class);
    $io->confirm('Do you want to make the Document field required on the Document media type?')
      ->willReturn(TRUE)
      ->shouldBeCalled();

    Update400::create($this->container)->requireDocumentMediaSourceField($io->reveal());

    $field = FieldConfig::loadByName('media', 'document', 'field_document');
    $this->assertTrue($field->isRequired());
  }

}
