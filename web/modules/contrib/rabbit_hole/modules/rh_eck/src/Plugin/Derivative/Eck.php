<?php

namespace Drupal\rh_eck\Plugin\Derivative;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;

/**
 * Class Eck.
 */
class Eck extends DeriverBase implements ContainerDeriverInterface {

  /**
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $entityStorage;

  /**
   * Creates a new class instance.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container to pull out services used in the fetcher.
   * @param string $base_plugin_id
   *   The base plugin ID for the plugin ID.
   *
   * @return static
   *   Returns an instance of this fetcher.
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    $entity_type = 'eck_entity_type';
    $entity_manager = $container->get('entity_type.manager');
    return new static(
      $entity_manager,
      $entity_manager->getStorage($entity_type)
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(\Drupal\Core\Entity\EntityTypeManager $manager, EntityStorageInterface $storage) {
    $this->entityManager = $manager;
    $this->entityStorage = $storage;
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $eck_contents = $this->entityStorage->loadMultiple();
    // Reset the discovered definitions.
    $this->derivatives = [];

    foreach ($eck_contents as $eck_content) {
      $derivative_key = $base_plugin_definition['id'] . '_' . $eck_content->id();
      $this->derivatives[$derivative_key] = $base_plugin_definition;
      $this->derivatives[$derivative_key]['id'] = $derivative_key;
      $this->derivatives[$derivative_key]['label'] = $eck_content->label();
      $this->derivatives[$derivative_key]['entityType'] = $eck_content->id();
    }
    return parent::getDerivativeDefinitions($base_plugin_definition);
  }

}
