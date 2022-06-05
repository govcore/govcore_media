<?php

namespace Drupal\govcore_media_audio\Plugin\media\Source;

use Drupal\govcore_media\FileInputExtensionMatchTrait;
use Drupal\govcore_media\InputMatchInterface;
use Drupal\media\Plugin\media\Source\AudioFile as CoreAudioFile;

/**
 * Input-matching version of the AudioFile media source.
 */
class AudioFile extends CoreAudioFile implements InputMatchInterface {

  use FileInputExtensionMatchTrait;

}
