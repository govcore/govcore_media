<?php

namespace Drupal\Tests\govcore_media_document\Kernel;

use Drupal\Core\Entity\Entity\EntityFormMode;
use Drupal\Core\Entity\Entity\EntityViewMode;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\media\Traits\MediaTypeCreationTrait;

/**
 * Tests GovCore Media Document's update path.
 *
 * @group govcore_media_document
 * @group govcore_media
 *
 * @covers govcore_media_document_update_8001()
 * @covers govcore_media_document_update_8002()
 */
class Update80018002Test extends KernelTestBase {

  use MediaTypeCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'field',
    'image',
    'govcore_media_document',
    'media',
    'media_test_source',
  ];

  /**
   * Tests the update function.
   */
  public function testUpdate() {
    $this->createMediaType('test', ['id' => 'document']);

    EntityViewMode::create([
      'targetEntityType' => 'media',
      'id' => 'media.thumbnail',
    ])->save();

    EntityFormMode::create([
      'targetEntityType' => 'media',
      'id' => 'media.media_library',
    ])->save();

    module_load_install('govcore_media_document');
    govcore_media_document_update_8001();
    govcore_media_document_update_8002();
  }

}
