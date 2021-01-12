<?php

namespace Drupal\bot_opening_hours;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of BOT Opening Hours Exception entities.
 *
 * @ingroup bot_opening_hours
 */
class BOTOpeningHoursExcListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('BOT Opening Hours Exception ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\bot_opening_hours\Entity\BOTOpeningHoursExc $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.bot_opening_hours_exc.edit_form',
      ['bot_opening_hours_exc' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
