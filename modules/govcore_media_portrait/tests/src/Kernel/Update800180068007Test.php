<?php

namespace Drupal\Tests\govcore_media_image\Kernel;

use Drupal\Core\Entity\Entity\EntityFormMode;
use Drupal\Core\Entity\Entity\EntityViewMode;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\media\Traits\MediaTypeCreationTrait;

/**
 * Tests GovCore Media Image's update path.
 *
 * @group govcore_media_image
 * @group govcore_media
 *
 * @covers govcore_media_image_update_8001()
 * @covers govcore_media_image_update_8006()
 * @covers govcore_media_image_update_8007()
 */
class Update800180068007Test extends KernelTestBase {

  use MediaTypeCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'field',
    'image',
    'govcore_media',
    'govcore_media_image',
    'media',
    'media_test_source',
  ];

  /**
   * Tests the update function.
   */
  public function testUpdate() {
    FieldStorageConfig::create([
      'type' => 'boolean',
      'entity_type' => 'media',
      'field_name' => 'field_media_in_library',
    ])->save();

    $this->createMediaType('test', ['id' => 'image']);

    EntityFormMode::create([
      'targetEntityType' => 'media',
      'id' => 'media.media_library',
    ])->save();

    EntityFormMode::create([
      'targetEntityType' => 'media',
      'id' => 'media.media_browser',
    ])->save();

    EntityViewMode::create([
      'targetEntityType' => 'media',
      'id' => 'media.thumbnail',
    ])->save();

    module_load_install('govcore_media_image');
    govcore_media_image_update_8001();
    govcore_media_image_update_8006();
    govcore_media_image_update_8007();
  }

}
