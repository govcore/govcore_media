<?php

namespace Drupal\Tests\govcore_media\Kernel\Update;

use Drupal\KernelTests\KernelTestBase;

/**
 * Tests GovCore Media's 8022 update hook.
 *
 * @group govcore_media
 */
class Update8022Test extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'govcore_media',
    'media',
    'system',
  ];

  /**
   * Tests GovCore Media's 8022 update hook.
   */
  public function testUpdate() {
    $setting = $this->config('govcore_media.settings')->get('revision_ui');
    $this->assertNull($setting);

    module_load_install('govcore_media');
    govcore_media_update_8022();

    $setting = $this->config('govcore_media.settings')->get('revision_ui');
    $this->assertTrue($setting);
  }

}
