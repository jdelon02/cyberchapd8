<?php

namespace Drupal\rh_eck\Plugin\RabbitHoleEntityPlugin;

use Drupal\rabbit_hole\Plugin\RabbitHoleEntityPluginBase;

/**
 * Implements rabbit hole behavior for nodes.
 *
 * @RabbitHoleEntityPlugin(
 *  id = "rh_eck",
 *  label = @Translation("Eck"),
 *  entityType = "eck_entity_type",
 *  deriver = "Drupal\rh_eck\Plugin\Derivative\Eck"
 * )
 */
class Eck extends RabbitHoleEntityPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getFormSubmitHandlerAttachLocations() {
    return [
      ['actions', 'submit', '#submit'],
    ];
  }

}
