<?php

namespace Drupal\Tests\govcore_media_audio\Kernel\Update;

use Drupal\Core\Entity\Entity\EntityFormMode;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\media\Traits\MediaTypeCreationTrait;

/**
 * Tests GovCore Media Audio's update path.
 *
 * @group govcore_media_audio
 * @group govcore_media
 *
 * @covers govcore_media_audio_update_8001()
 */
class Update8001Test extends KernelTestBase {

  use MediaTypeCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'field',
    'govcore_media_audio',
    'media',
    'media_test_source',
  ];

  /**
   * Tests the update function.
   */
  public function testUpdate() {
    $this->createMediaType('test', [
      'id' => 'audio_file',
    ]);

    EntityFormMode::create([
      'targetEntityType' => 'media',
      'id' => 'media.media_library',
    ])->save();

    module_load_install('govcore_media_audio');
    govcore_media_audio_update_8001();

    $form_display = $this->container->get('entity_display.repository')
      ->getFormDisplay('media', 'audio_file', 'media_library');
    $this->assertFalse($form_display->isNew());
    $this->assertSame('media.audio_file.media_library', $form_display->id());
    $this->assertSame('audio_file', $form_display->getTargetBundle());
    $hidden_components = $form_display->get('hidden');
    $this->assertArrayNotHasKey('field_media_audio_file', $hidden_components);
    $this->assertTrue($hidden_components['field_media_test']);
  }

}
