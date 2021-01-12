<?php

namespace Drupal\bot_opening_hours;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the BOT Opening Hours Exception entity.
 *
 * @see \Drupal\bot_opening_hours\Entity\BOTOpeningHoursExc.
 */
class BOTOpeningHoursExcAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\bot_opening_hours\Entity\BOTOpeningHoursExcInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished bot opening hours exception entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published bot opening hours exception entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit bot opening hours exception entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete bot opening hours exception entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add bot opening hours exception entities');
  }

}
