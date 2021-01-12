<?php

namespace Drupal\bot_event_instance_entities\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Event Instance entities.
 *
 * @ingroup bot_event_instance_entities
 */
interface EventInstanceEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Event Instance name.
   *
   * @return string
   *   Name of the Event Instance.
   */
  public function getName();

  /**
   * Sets the Event Instance name.
   *
   * @param string $name
   *   The Event Instance name.
   *
   * @return \Drupal\bot_event_instance_entities\Entity\EventInstanceEntityInterface
   *   The called Event Instance entity.
   */
  public function setName($name);

  /**
   * Gets the Event Instance creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Event Instance.
   */
  public function getCreatedTime();

  /**
   * Sets the Event Instance creation timestamp.
   *
   * @param int $timestamp
   *   The Event Instance creation timestamp.
   *
   * @return \Drupal\bot_event_instance_entities\Entity\EventInstanceEntityInterface
   *   The called Event Instance entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Event Instance revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Event Instance revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\bot_event_instance_entities\Entity\EventInstanceEntityInterface
   *   The called Event Instance entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Event Instance revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Event Instance revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\bot_event_instance_entities\Entity\EventInstanceEntityInterface
   *   The called Event Instance entity.
   */
  public function setRevisionUserId($uid);

}
