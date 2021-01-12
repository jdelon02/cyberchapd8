<?php

namespace Drupal\media_entity_remote_image\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the RemoteImageUrl constraint.
 */
class RemoteImageUrlConstraintValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($value, Constraint $constraint) {

    $value = $value->getValue();
    $value = reset($value);
    $uri = $value['uri'];
    $ext = pathinfo($uri, PATHINFO_EXTENSION);
    $ext = strtolower($ext);

    if (!in_array($ext, $constraint->allowedExtensions)) {
      // This doesn't properly target the URL/uri field.
      $this->context->buildViolation($constraint->message)
        ->atPath('uri')
        ->addViolation();
    }
  }

}
