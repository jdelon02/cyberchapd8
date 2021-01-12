<?php

namespace Drupal\bot_opening_hours\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining BOT Opening Hours Exception entities.
 *
 * @ingroup bot_opening_hours
 */
interface BOTOpeningHoursExcInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the BOT Opening Hours Exception name.
   *
   * @return string
   *   Name of the BOT Opening Hours Exception.
   */
  public function getName();

  /**
   * Sets the BOT Opening Hours Exception name.
   *
   * @param string $name
   *   The BOT Opening Hours Exception name.
   *
   * @return \Drupal\bot_opening_hours\Entity\BOTOpeningHoursExcInterface
   *   The called BOT Opening Hours Exception entity.
   */
  public function setName($name);

  /**
   * Gets the BOT Opening Hours Exception creation timestamp.
   *
   * @return int
   *   Creation timestamp of the BOT Opening Hours Exception.
   */
  public function getCreatedTime();

  /**
   * Sets the BOT Opening Hours Exception creation timestamp.
   *
   * @param int $timestamp
   *   The BOT Opening Hours Exception creation timestamp.
   *
   * @return \Drupal\bot_opening_hours\Entity\BOTOpeningHoursExcInterface
   *   The called BOT Opening Hours Exception entity.
   */
  public function setCreatedTime($timestamp);

}
