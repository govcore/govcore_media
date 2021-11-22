<?php

namespace Drupal\Tests\govcore_media\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\govcore_core\ConfigHelper as Config;
use Drupal\Tests\media\Traits\MediaTypeCreationTrait;

/**
 * Tests of API-level GovCore functionality related to media types.
 *
 * @group govcore_media
 */
class MediaTypeTest extends KernelTestBase {

  use MediaTypeCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'field',
    'file',
    'image',
    'govcore_core',
    'govcore_media',
    'media',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    Config::forModule('govcore_media')
      ->getEntity('field_storage_config', 'media.field_media_in_library')
      ->save();
  }

  /**
   * Tests that field_media_in_library is auto-cloned for new media bundles.
   */
  public function testCloneMediaInLibraryField() {
    $type = $this->createMediaType('file')->id();

    /** @var \Drupal\media\MediaInterface $media */
    $media = $this->container
      ->get('entity_type.manager')
      ->getStorage('media')
      ->create([
        'bundle' => $type,
      ]);

    $this->assertTrue($media->hasField('field_media_in_library'));

    // The field should be present in the form as a checkbox.
    $component = $this->container->get('entity_display.repository')
      ->getFormDisplay('media', $type)
      ->getComponent('field_media_in_library');

    $this->assertIsArray($component);
  }

}
