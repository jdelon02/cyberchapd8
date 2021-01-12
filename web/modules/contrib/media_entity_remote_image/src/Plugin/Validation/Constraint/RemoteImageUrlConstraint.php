<?php

namespace Drupal\media_entity_remote_image\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Check if a value is a valid URL.
 *
 * @constraint(
 *   id = "RemoteImageUrl",
 *   label = @Translation("Remote image Url", context = "Validation"),
 *   type = { "link", "string", "string_long" }
 * )
 */
class RemoteImageUrlConstraint extends Constraint {

  /**
   * Valid file extensions.
   *
   * @var string[]
   */
  public $allowedExtensions = [
    "gif",
    "jpg",
    "jpeg",
    "png",
    "svg",
    "tiff",
    "tif",
  ];


  /**
   * The default violation message.
   *
   * @var string
   */
  public $message = 'Not a valid image URL. Currently, valid file extensions include .gif, .jpeg, .jpg, .png, .svg, .tif, and .tiff.';

}
