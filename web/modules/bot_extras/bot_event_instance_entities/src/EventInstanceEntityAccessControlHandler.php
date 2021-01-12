<?php

namespace Drupal\bot_event_instance_entities;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Event Instance entity.
 *
 * @see \Drupal\bot_event_instance_entities\Entity\EventInstanceEntity.
 */
class EventInstanceEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\bot_event_instance_entities\Entity\EventInstanceEntityInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished event instance entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published event instance entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit event instance entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete event instance entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add event instance entities');
  }


}
