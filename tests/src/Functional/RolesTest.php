<?php

namespace Drupal\Tests\govcore_media\Functional;

use Drupal\Core\Config\FileStorage;
use Drupal\Core\Config\InstallStorage;
use Drupal\media\Entity\Media;
use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\media\Traits\MediaTypeCreationTrait;
use Drupal\user\Entity\Role;

/**
 * Tests functionality of optional 'media_creator' and 'media_manager' roles.
 *
 * @group govcore_media
 */
class RolesTest extends BrowserTestBase {

  use MediaTypeCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'block',
    'govcore_media',
    'media_test_source',
  ];

  /**
   * The ID of the media type created for the test.
   *
   * @var string
   */
  protected $mediaType;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->mediaType = $this->createMediaType('test')->id();

    $dir = __DIR__ . '/../../../' . InstallStorage::CONFIG_OPTIONAL_DIRECTORY;
    $storage = new FileStorage($dir);

    Role::create($storage->read('user.role.media_creator'))->save();
    Role::create($storage->read('user.role.media_manager'))->save();

    $this->drupalPlaceBlock('local_tasks_block');
  }

  /**
   * Tests the functionality of the 'media_creator' and 'media_manager' roles.
   */
  public function testRoles() {
    $page = $this->getSession()->getPage();

    $account = $this->drupalCreateUser();
    $account->addRole('media_creator');
    $account->save();
    $this->drupalLogin($account);

    $media = Media::create([
      'bundle' => $this->mediaType,
      'name' => $this->getRandomGenerator()->word(16),
      'uid' => $account->id(),
    ]);
    $media->setPublished();
    $media->save();

    $assert = $this->assertSession();
    $this->drupalGet('/admin/content/media');
    $page->clickLink($media->label());
    $assert->statusCodeEquals(200);
    $assert->linkExists('Edit');
    $assert->linkExists('Delete');

    $this->drupalLogout();

    $account = $this->drupalCreateUser();
    $account->addRole('media_manager');
    $account->save();
    $this->drupalLogin($account);

    $this->drupalGet('/admin/content/media');
    $page->clickLink($media->label());
    $assert->statusCodeEquals(200);
    $assert->linkExists('Edit');
    $assert->linkExists('Delete');
  }

}
