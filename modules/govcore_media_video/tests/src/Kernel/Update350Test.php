<?php

namespace Drupal\Tests\govcore_media_video\Kernel;

use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\KernelTests\KernelTestBase;
use Drupal\govcore_media_video\Update\Update350;
use Drupal\Tests\media\Traits\MediaTypeCreationTrait;
use Prophecy\Argument;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * Tests configuration updates targeting GovCore Media Video 3.5.0.
 *
 * @group govcore_media
 * @group govcore_media_video
 *
 * @coversDefaultClass \Drupal\govcore_media_video\Update\Update350
 */
class Update350Test extends KernelTestBase {

  use MediaTypeCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'field',
    'media',
    'media_test_source',
    'system',
    'user',
  ];

  /**
   * @covers ::removeVideoFileLibraryFieldTranslatability
   * @covers ::removeVideoLibraryFieldTranslatability
   */
  public function test() {
    $this->createMediaType('test', [
      'id' => 'video',
    ]);
    $this->createMediaType('test', [
      'id' => 'video_file',
    ]);

    $field_storage = FieldStorageConfig::create([
      'type' => 'boolean',
      'entity_type' => 'media',
      'field_name' => 'field_media_in_library',
    ]);
    $field_storage->save();

    FieldConfig::create([
      'field_storage' => $field_storage,
      'bundle' => 'video',
      'translatable' => TRUE,
    ])->save();

    FieldConfig::create([
      'field_storage' => $field_storage,
      'bundle' => 'video_file',
      'translatable' => TRUE,
    ])->save();

    $io = $this->prophesize(StyleInterface::class);
    $io->confirm(Argument::type('string'))->willReturn(TRUE);

    $task = Update350::create($this->container);
    $task->removeVideoFileLibraryFieldTranslatability($io->reveal());
    $task->removeVideoLibraryFieldTranslatability($io->reveal());

    $this->assertFalse(
      FieldConfig::loadByName('media', 'video_file', 'field_media_in_library')->isTranslatable()
    );
    $this->assertFalse(
      FieldConfig::loadByName('media', 'video', 'field_media_in_library')->isTranslatable()
    );
  }

}
