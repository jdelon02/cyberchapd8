<?php

namespace Drupal\bot_event_instance_entities;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Event Instance entities.
 *
 * @ingroup bot_event_instance_entities
 */
class EventInstanceEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Event Instance ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\bot_event_instance_entities\Entity\EventInstanceEntity $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.event_instance_entity.edit_form',
      ['event_instance_entity' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
