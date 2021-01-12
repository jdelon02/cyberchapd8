<?php

namespace Drupal\backoffice_thinking_alert_bar\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Alert Bar Message entities.
 *
 * @ingroup backoffice_thinking_alert_bar
 */
interface AlertBarInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Alert Bar Message name.
   *
   * @return string
   *   Name of the Alert Bar Message.
   */
  public function getName();

  /**
   * Sets the Alert Bar Message name.
   *
   * @param string $name
   *   The Alert Bar Message name.
   *
   * @return \Drupal\backoffice_thinking_alert_bar\Entity\AlertBarInterface
   *   The called Alert Bar Message entity.
   */
  public function setName($name);

  /**
   * Gets the Alert Bar Message creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Alert Bar Message.
   */
  public function getCreatedTime();

  /**
   * Sets the Alert Bar Message creation timestamp.
   *
   * @param int $timestamp
   *   The Alert Bar Message creation timestamp.
   *
   * @return \Drupal\backoffice_thinking_alert_bar\Entity\AlertBarInterface
   *   The called Alert Bar Message entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Alert Bar Message revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Alert Bar Message revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\backoffice_thinking_alert_bar\Entity\AlertBarInterface
   *   The called Alert Bar Message entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Alert Bar Message revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Alert Bar Message revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\backoffice_thinking_alert_bar\Entity\AlertBarInterface
   *   The called Alert Bar Message entity.
   */
  public function setRevisionUserId($uid);

}
