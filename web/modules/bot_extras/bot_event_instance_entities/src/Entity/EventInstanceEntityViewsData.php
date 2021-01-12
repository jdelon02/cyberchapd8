<?php

namespace Drupal\bot_event_instance_entities\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Event Instance entities.
 */
class EventInstanceEntityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
