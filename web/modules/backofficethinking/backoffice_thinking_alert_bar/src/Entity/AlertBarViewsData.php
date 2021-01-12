<?php

namespace Drupal\backoffice_thinking_alert_bar\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Alert Bar Message entities.
 */
class AlertBarViewsData extends EntityViewsData {

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
