<?php

namespace Drupal\bot_event_instance_entities;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\bot_event_instance_entities\Entity\EventInstanceEntityInterface;

/**
 * Defines the storage handler class for Event Instance entities.
 *
 * This extends the base storage class, adding required special handling for
 * Event Instance entities.
 *
 * @ingroup bot_event_instance_entities
 */
class EventInstanceEntityStorage extends SqlContentEntityStorage implements EventInstanceEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(EventInstanceEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {event_instance_entity_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {event_instance_entity_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(EventInstanceEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {event_instance_entity_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('event_instance_entity_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
