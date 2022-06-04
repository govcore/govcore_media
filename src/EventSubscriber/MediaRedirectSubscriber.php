<?php

namespace Drupal\govcore_media\EventSubscriber;

use Drupal\Core\File\FileSystem;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Renders the referenced file at media entity urls.
 *
 * @package Drupal\govcore_media\EventSubscriber
 */
class MediaRedirectSubscriber implements EventSubscriberInterface {

  /**
   * The file system service.
   *
   * @var FileSystem
   */
  protected $fileSystem;

  /**
   * MediaRedirectSubscriber constructor.
   *
   * @param FileSystem $file_system
   */
  public function __construct(FileSystem $file_system) {
    $this->fileSystem = $file_system;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    // Redirect to files contained within media.
    // Use 27 as a priority to do not be impacted by caches.
    $events[KernelEvents::REQUEST][] = ['onRequestRedirectToFile', 27];

    return $events;
  }

  /**
   * Redirect standalone media to the contained file.
   *
   * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
   *   The event to process.
   */
  public function onRequestRedirectToFile(GetResponseEvent $event) {
    $route_match = \Drupal::routeMatch();
    if (\Drupal::currentUser()->hasPermission('update any media')) {
      return $route_match;
    } else {
      if ($route_match->getRouteName() == 'entity.media.canonical') {
        $entity = $route_match->getParameter('media');
        $settings = $this->config('govcore_media.settings');
        $redirect_bundles = $settings->get('redirect_bundles');
        $file_bundle = $entity->bundle();
        $file_entity_bundle = in_array($file_bundle, $redirect_bundles);
        if ($redirect_bundles[$file_bundle] == TRUE) {
          switch ($file_entity_bundle) {
            case 'image':
              /** @var \Drupal\file\Entity\File $file */
              $file = $entity->field_media_image->entity;
              break;
            case 'document':
              /** @var \Drupal\file\Entity\File $file */
              $file = $entity->field_media_document->entity;
              break;
            case 'portrait':
              /** @var \Drupal\file\Entity\File $file */
              $file = $entity->field_media_image->entity;
              break;
            case 'slider_image':
              /** @var \Drupal\file\Entity\File $file */
              $file = $entity->field_media_image->entity;
              break;
            case 'widescreen':
              /** @var \Drupal\file\Entity\File $file */
              $file = $entity->field_media_image->entity;
              break;
            default:
              break;
          }
        }
        if (isset($file) && $file->createFileUrl()) {
          $uri = $file->getFileUri();

          $server_path = $this->fileSystem->realpath($uri);

          // TODO: ensure access control is applied to private / unpublished files

          // Set the file to expire in 4 hrs.
          $headers = [
            'Expires' => date(DATE_RFC822, strtotime('+4 hours')),
          ];
          // Ensure etag and last modified are auto populated.
          // @see \Symfony\Component\HttpFoundation\BinaryFileResponse
          $response = new BinaryFileResponse($server_path, 200, $headers, TRUE, NULL, TRUE, TRUE);

          // Set cache header to expire in 4 hrs.
          $response->setCache([
            'max_age' => 840,
            'private' => FALSE,
            'public' => TRUE,
          ]);

          // If file is not modified, send 304 not modified.
          $request = $event->getRequest();
          if ($response->isNotModified($request)) {
            $response->send();
          }
          else {
            $event->setResponse($response);
          }
        }
      }
    }
  }
}
