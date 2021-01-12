<?php

namespace Drupal\backoffice_thinking_alert_bar;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Alert Bar Message entities.
 *
 * @ingroup backoffice_thinking_alert_bar
 */
class AlertBarListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Alert Bar Message ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\backoffice_thinking_alert_bar\Entity\AlertBar $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.alert_bar_message.edit_form',
      ['alert_bar_message' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
