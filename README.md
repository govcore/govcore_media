# GovCore Media
GovCore Media provides modules and configuration for great media authoring
experiences.

### Components
GovCore Media's components are not enabled by default. You can install them
like any other Drupal modules.

#### Media Audio (`govcore_media_audio`)
Provides an Audio file media type, which can be used to store and play audio files like
MP3s, WAVs, or AACs.

#### Bulk Media Upload (`govcore_media_bulk_upload`)
Leverages the [DropzoneJS](https://drupal.org/project/dropzonejs) module to
provide a form for uploading media assets in bulk.

#### Media Document (`govcore_media_document`)
Provides a Document media type, which can be used for storing files, such as
PDFs, or other types of documents.

#### Media Image (`govcore_media_image`)
Provides an Image media type, which can be used for storing and displaying
images. If the [Image Widget Crop](https://drupal.org/project/image_widget_crop)
module is installed, the Image media type will automatically integrate with
it to provide cropping functionality.

#### Media Instagram (`govcore_media_instagram`)
Provides an Instagram media type, for referencing and displaying Instagram
posts in your Drupal site using the
[Media Entity Instagram](https://drupal.org/project/media_entity_instagram)
module.

#### Media Slideshow (`govcore_media_slideshow`)
Provides a Slideshow block type type, which allows you to reference any number
of assets from your media library and display them as a slideshow or carousel
using the [Slick](https://drupal.org/project/slick) library.

#### Media Twitter (`govcore_media_twitter`)
Provides a Tweet media type, for referencing and displaying tweets in your
Drupal site using the
[Media Entity Twitter](https://drupal.org/project/media_entity_twitter)
module.

#### Media Video (`govcore_media_video`)
Provides a Video media type for displaying remote videos (e.g., YouTube or
Vimeo) in your Drupal site and the Video file media type for managing locally
hosted videos.

#### Updates
GovCore Media and its components use the normal Drupal database update system
as often as possible. However, there are occasionally certain updates which
touch configuration and may change the functionality of your site. These updates
are optional, and are performed by a special utility at the command line. This
utility is compatible with [Drush](https://drush.org) 9 or later.

To run updates using Drush:

`
drush update:govcore
`

#### Known Issues
* If you upload an image into an image field using the new image browser, you
  can set the image's alt text at upload time, but that text will not be
  replicated to the image field. This is due to a limitation of Entity Browser's
  API.
* Using the bulk upload feature in environments with a load balancer might
  result in some images not being saved.
