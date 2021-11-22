<?php

namespace Drupal\govcore_media_image\Plugin\media\Source;

use Drupal\govcore_media\FileInputExtensionMatchTrait;
use Drupal\govcore_media\InputMatchInterface;
use Drupal\media\Plugin\media\Source\Image as BaseImage;

/**
 * Input-matching version of the Image media source.
 */
class Image extends BaseImage implements InputMatchInterface {

  use FileInputExtensionMatchTrait;

}
