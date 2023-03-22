<?php

namespace Drupal\Tests\govcore_media\Kernel;

use Drupal\field\Entity\FieldStorageConfig;
use Drupal\file\Entity\File;
use Drupal\KernelTests\KernelTestBase;
use Drupal\govcore_media\MediaHelper;
use Drupal\media\Entity\Media;
use Drupal\Tests\media\Traits\MediaTypeCreationTrait;
use Drupal\Tests\TestFileCreationTrait;

/**
 * @group govcore_media
 *
 * @coversDefaultClass \Drupal\govcore_media\MediaHelper
 */
class MediaHelperTest extends KernelTestBase {

  use MediaTypeCreationTrait;
  use TestFileCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'field',
    'file',
    'image',
    'govcore_media',
    'media',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('file');

    FieldStorageConfig::create([
      'entity_type' => 'media',
      'type' => 'boolean',
      'field_name' => 'field_media_in_library',
    ])->save();
  }

  /**
   * @covers ::prepareFileDestination
   * @covers ::getSourceField
   */
  public function testPrepareFileDestination() {
    $media_type = $this->createMediaType('file');

    $media = Media::create([
      'bundle' => $media_type->id(),
    ]);

    /** @var \Drupal\field\Entity\FieldConfig $source_field */
    $source_field = $media->getSource()->getSourceFieldDefinition($media_type);
    $source_field->setSetting('file_directory', 'wambooli')->save();

    $file = File::create([
      'uri' => $this->generateFile('foo', 80, 10),
    ]);
    $file->save();

    $media->set($source_field->getName(), $file->id());

    $this->assertDirectoryDoesNotExist('public://wambooli');
    MediaHelper::prepareFileDestination($media);
    $this->assertDirectoryExists('public://wambooli');
  }

}
