<?php

namespace Drupal\bot_event_instance_entities;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface EventInstanceEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Event Instance revision IDs for a specific Event Instance.
   *
   * @param \Drupal\bot_event_instance_entities\Entity\EventInstanceEntityInterface $entity
   *   The Event Instance entity.
   *
   * @return int[]
   *   Event Instance revision IDs (in ascending order).
   */
  public function revisionIds(EventInstanceEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Event Instance author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Event Instance revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\bot_event_instance_entities\Entity\EventInstanceEntityInterface $entity
   *   The Event Instance entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(EventInstanceEntityInterface $entity);

  /**
   * Unsets the language for all Event Instance with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
