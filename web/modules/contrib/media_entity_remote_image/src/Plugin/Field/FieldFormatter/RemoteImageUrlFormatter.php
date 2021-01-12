<?php

namespace Drupal\media_entity_remote_image\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\link\Plugin\Field\FieldFormatter\LinkFormatter;

/**
 * Remote image URL field formatter.
 *
 * @FieldFormatter(
 *   id = "remote_image_url_formatter",
 *   label = @Translation("Image with metatags"),
 *   description = @Translation("Display the remote image along with its associated metatags."),
 *   field_types = {
 *     "remote_image_url"
 *   }
 * )
 */
class RemoteImageUrlFormatter extends LinkFormatter {

  /**
   * @inheritDoc
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);
    $values = $items->getValue();

    foreach ($elements as $delta => $entity) {
      $elements[$delta] = [
        '#type' => 'markup',
        '#markup' => "<img src='{$values[$delta]['uri']}' alt='{$values[$delta]['alt']}'>",
        '#allowed_tags' => ['img'],
      ];
    }

    return $elements;
  }

}
