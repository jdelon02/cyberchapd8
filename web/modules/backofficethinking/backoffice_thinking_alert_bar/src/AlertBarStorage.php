<?php

namespace Drupal\backoffice_thinking_alert_bar;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\backoffice_thinking_alert_bar\Entity\AlertBarInterface;

/**
 * Defines the storage handler class for Alert Bar Message entities.
 *
 * This extends the base storage class, adding required special handling for
 * Alert Bar Message entities.
 *
 * @ingroup backoffice_thinking_alert_bar
 */
class AlertBarStorage extends SqlContentEntityStorage implements AlertBarStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(AlertBarInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {alert_bar_message_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {alert_bar_message_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(AlertBarInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {alert_bar_message_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('alert_bar_message_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
