<?php

namespace Drupal\Tests\govcore_media_instagram\Functional;

use Drupal\language\Entity\ConfigurableLanguage;
use Drupal\media\Entity\Media;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests the translatability of the field_media_in_library field.
 *
 * @group govcore_media
 * @group govcore_media_instagram
 *
 * @requires module media_entity_instagram
 */
class LibraryInclusionTranslationTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'content_translation',
    'govcore_media_instagram',
  ];

  /**
   * Tests that the 'field_media_in_library' field is not translatable.
   */
  public function test() {
    ConfigurableLanguage::createFromLangcode('hu')->save();

    $media = Media::create([
      'bundle' => 'instagram',
      'name' => $this->randomString(),
      'embed_code' => 'https://www.instagram.com/p/CGkIkLngLDS',
      'field_media_in_library' => TRUE,
    ]);
    $media->addTranslation('hu', [
      'field_media_in_library' => FALSE,
    ]);
    $this->assertSame(SAVED_NEW, $media->save());

    $this->assertTrue($media->field_media_in_library->value);
    $this->assertTrue($media->getTranslation('hu')->field_media_in_library->value);
  }

}
