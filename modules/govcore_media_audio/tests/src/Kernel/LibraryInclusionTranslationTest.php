<?php

namespace Drupal\Tests\govcore_media_audio\Kernel;

use Drupal\file\Entity\File;
use Drupal\KernelTests\KernelTestBase;
use Drupal\language\Entity\ConfigurableLanguage;
use Drupal\media\Entity\Media;

/**
 * Tests that field_media_in_library is not translatable.
 *
 * @group govcore_media
 * @group govcore_media_audio
 */
class LibraryInclusionTranslationTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['system', 'user'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('user');

    $this->container->get('module_installer')->install([
      'content_translation',
      'govcore_media_audio',
    ]);
    ConfigurableLanguage::createFromLangcode('hu')->save();
  }

  /**
   * Tests that field_media_in_library is not translatable.
   */
  public function test() {
    $uri = uniqid('public://') . '.mp3';
    $this->assertGreaterThan(0, file_put_contents($uri, $this->getRandomGenerator()->paragraphs()));

    $file = File::create(['uri' => $uri]);
    $file->save();

    $media = Media::create([
      'bundle' => 'audio',
      'name' => $this->randomString(),
      'field_media_audio_file' => $file->id(),
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
