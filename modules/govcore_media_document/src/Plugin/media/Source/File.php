<?php

namespace Drupal\govcore_media_document\Plugin\media\Source;

use Drupal\govcore_media\FileInputExtensionMatchTrait;
use Drupal\govcore_media\InputMatchInterface;
use Drupal\media\Plugin\media\Source\File as BaseFile;

/**
 * Input-matching version of the File media source.
 */
class File extends BaseFile implements InputMatchInterface {

  use FileInputExtensionMatchTrait;

}
