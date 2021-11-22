<?php

namespace Drupal\Tests\govcore_media_video\Kernel;

use Drupal\Core\Entity\Entity\EntityViewMode;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\media\Traits\MediaTypeCreationTrait;

/**
 * Tests GovCore Media Video's update path.
 *
 * @group govcore_media_video
 * @group govcore_media
 *
 * @covers govcore_media_video_update_8003()
 */
class Update8003Test extends KernelTestBase {

  use MediaTypeCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'field',
    'image',
    'govcore_media_video',
    'media',
    'media_test_source',
  ];

  /**
   * Tests the update function.
   */
  public function testUpdate() {
    $this->createMediaType('test', [
      'id' => 'video',
    ]);

    EntityViewMode::create([
      'targetEntityType' => 'media',
      'id' => 'media.thumbnail',
    ])->save();

    module_load_install('govcore_media_video');
    govcore_media_video_update_8003();

    $view_display = $this->container->get('entity_display.repository')
      ->getViewDisplay('media', 'video', 'thumbnail');
    $this->assertFalse($view_display->isNew());
    $this->assertSame('media.video.thumbnail', $view_display->id());
    $this->assertSame('video', $view_display->getTargetBundle());
    $hidden_components = $view_display->get('hidden');
    $this->assertArrayNotHasKey('field_media_oembed_video', $hidden_components);
    $this->assertTrue($hidden_components['field_media_test']);
  }

}
