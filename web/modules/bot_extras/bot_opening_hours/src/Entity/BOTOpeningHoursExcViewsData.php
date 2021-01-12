<?php

namespace Drupal\bot_opening_hours\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for BOT Opening Hours Exception entities.
 */
class BOTOpeningHoursExcViewsData extends EntityViewsData {

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
