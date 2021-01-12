<?php

namespace Drupal\backoffice_thinking_alert_bar;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface AlertBarStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Alert Bar Message revision IDs for a specific Alert Bar Message.
   *
   * @param \Drupal\backoffice_thinking_alert_bar\Entity\AlertBarInterface $entity
   *   The Alert Bar Message entity.
   *
   * @return int[]
   *   Alert Bar Message revision IDs (in ascending order).
   */
  public function revisionIds(AlertBarInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Alert Bar Message author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Alert Bar Message revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\backoffice_thinking_alert_bar\Entity\AlertBarInterface $entity
   *   The Alert Bar Message entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(AlertBarInterface $entity);

  /**
   * Unsets the language for all Alert Bar Message with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
