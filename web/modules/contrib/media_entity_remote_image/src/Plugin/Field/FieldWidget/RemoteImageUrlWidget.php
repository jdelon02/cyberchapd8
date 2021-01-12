<?php

namespace Drupal\media_entity_remote_image\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\link\Plugin\Field\FieldWidget\LinkWidget;

/**
 * Remote image URL field widget.
 *
 * @FieldWidget(
 *   id = "remote_image_url_widget",
 *   label = @Translation("Remote Image Url"),
 *   description = @Translation("A plaintext field for a remote image url plus fields for metadata."),
 *   field_types = {
 *     "remote_image_url"
 *   }
 * )
 */
class RemoteImageUrlWidget extends LinkWidget {

  /**
   * @inheritDoc
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $element = parent::formElement($items, $delta, $element, $form, $form_state);

    unset($element['title']);
    unset($element['attributes']);

    $element['alt'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Alt text'),
      // '#placeholder' => $this->getSetting('placeholder_title'),
      '#default_value' => isset($items[$delta]->alt) ? $items[$delta]->alt : NULL,
      '#maxlength' => 255,
      // '#access' => $this->getFieldSetting('title') != DRUPAL_DISABLED,
      '#required' => (\Drupal::routeMatch()->getRouteName() === 'entity.field_config.media_field_edit_form') ? FALSE : TRUE,
      '#description' => $this->t('Short description of the image used by screen readers and displayed when the image is not loaded. This is important for accessibility.'),
    ];

    return $element;
  }

}
