<?php

namespace Drupal\govcore_media_twitter\Plugin\media\Source;

use Drupal\govcore_media\InputMatchInterface;
use Drupal\govcore_media\ValidationConstraintMatchTrait;
use Drupal\media_entity_twitter\Plugin\media\Source\Twitter as BaseTwitter;

/**
 * Input-matching version of the Twitter media source.
 */
class Twitter extends BaseTwitter implements InputMatchInterface {

  use ValidationConstraintMatchTrait;

}
