<?php

namespace Drupal\bot_event_instance_entities\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting a Event Instance revision.
 *
 * @ingroup bot_event_instance_entities
 */
class EventInstanceEntityRevisionDeleteForm extends ConfirmFormBase {

  /**
   * The Event Instance revision.
   *
   * @var \Drupal\bot_event_instance_entities\Entity\EventInstanceEntityInterface
   */
  protected $revision;

  /**
   * The Event Instance storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $eventInstanceEntityStorage;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->eventInstanceEntityStorage = $container->get('entity_type.manager')->getStorage('event_instance_entity');
    $instance->connection = $container->get('database');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'event_instance_entity_revision_delete_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete the revision from %revision-date?', [
      '%revision-date' => format_date($this->revision->getRevisionCreationTime()),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.event_instance_entity.version_history', ['event_instance_entity' => $this->revision->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $event_instance_entity_revision = NULL) {
    $this->revision = $this->EventInstanceEntityStorage->loadRevision($event_instance_entity_revision);
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->EventInstanceEntityStorage->deleteRevision($this->revision->getRevisionId());

    $this->logger('content')->notice('Event Instance: deleted %title revision %revision.', ['%title' => $this->revision->label(), '%revision' => $this->revision->getRevisionId()]);
    $this->messenger()->addMessage(t('Revision from %revision-date of Event Instance %title has been deleted.', ['%revision-date' => format_date($this->revision->getRevisionCreationTime()), '%title' => $this->revision->label()]));
    $form_state->setRedirect(
      'entity.event_instance_entity.canonical',
       ['event_instance_entity' => $this->revision->id()]
    );
    if ($this->connection->query('SELECT COUNT(DISTINCT vid) FROM {event_instance_entity_field_revision} WHERE id = :id', [':id' => $this->revision->id()])->fetchField() > 1) {
      $form_state->setRedirect(
        'entity.event_instance_entity.version_history',
         ['event_instance_entity' => $this->revision->id()]
      );
    }
  }

}
