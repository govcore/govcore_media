<?php

namespace Drupal\Tests\govcore_media\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests the GovCore Media settings form.
 *
 * @group govcore_media
 */
class SettingsFormTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['govcore_media'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Tests that administrators can access the settings form.
   */
  public function testAccess(): void {
    $account = $this->drupalCreateUser([], NULL, TRUE);
    $this->drupalLogin($account);
    $this->drupalGet('/admin/config/system/govcore/media');
    $this->assertSession()->statusCodeEquals(200);
  }

}
