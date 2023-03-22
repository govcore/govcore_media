<?php

/**
 * @file
 * Contains post-update functions for GovCore Media.
 */

/**
 * Implements hook_removed_post_updates().
 */
function govcore_media_removed_post_updates(): array {
  return [
    'govcore_media_post_update_change_action_plugins' => '5.0.0',
  ];
}
