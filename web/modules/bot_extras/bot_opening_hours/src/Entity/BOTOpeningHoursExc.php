<?php

namespace Drupal\bot_opening_hours\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the BOT Opening Hours Exception entity.
 *
 * @ingroup bot_opening_hours
 *
 * @ContentEntityType(
 *   id = "bot_opening_hours_exc",
 *   label = @Translation("BOT Opening Hours Exception"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\bot_opening_hours\BOTOpeningHoursExcListBuilder",
 *     "views_data" = "Drupal\bot_opening_hours\Entity\BOTOpeningHoursExcViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\bot_opening_hours\Form\BOTOpeningHoursExcForm",
 *       "add" = "Drupal\bot_opening_hours\Form\BOTOpeningHoursExcForm",
 *       "edit" = "Drupal\bot_opening_hours\Form\BOTOpeningHoursExcForm",
 *       "delete" = "Drupal\bot_opening_hours\Form\BOTOpeningHoursExcDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\bot_opening_hours\BOTOpeningHoursExcHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\bot_opening_hours\BOTOpeningHoursExcAccessControlHandler",
 *   },
 *   base_table = "bot_opening_hours_exc",
 *   translatable = FALSE,
 *   admin_permission = "administer bot opening hours exception entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/bot_opening_hours_exc/{bot_opening_hours_exc}",
 *     "add-form" = "/admin/structure/bot_opening_hours_exc/add",
 *     "edit-form" = "/admin/structure/bot_opening_hours_exc/{bot_opening_hours_exc}/edit",
 *     "delete-form" = "/admin/structure/bot_opening_hours_exc/{bot_opening_hours_exc}/delete",
 *     "collection" = "/admin/structure/bot_opening_hours_exc",
 *   },
 *   field_ui_base_route = "bot_opening_hours_exc.settings"
 * )
 */
class BOTOpeningHoursExc extends ContentEntityBase implements BOTOpeningHoursExcInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the BOT Opening Hours Exception entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the BOT Opening Hours Exception entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['status']->setDescription(t('A boolean indicating whether the BOT Opening Hours Exception is published.'))
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
