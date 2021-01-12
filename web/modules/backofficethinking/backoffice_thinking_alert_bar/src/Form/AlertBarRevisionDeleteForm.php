<?php

namespace Drupal\backoffice_thinking_alert_bar\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting a Alert Bar Message revision.
 *
 * @ingroup backoffice_thinking_alert_bar
 */
class AlertBarRevisionDeleteForm extends ConfirmFormBase {

  /**
   * The Alert Bar Message revision.
   *
   * @var \Drupal\backoffice_thinking_alert_bar\Entity\AlertBarInterface
   */
  protected $revision;

  /**
   * The Alert Bar Message storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $alertBarStorage;

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
    $instance->alertBarStorage = $container->get('entity_type.manager')->getStorage('alert_bar_message');
    $instance->connection = $container->get('database');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'alert_bar_message_revision_delete_confirm';
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
    return new Url('entity.alert_bar_message.version_history', ['alert_bar_message' => $this->revision->id()]);
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
  public function buildForm(array $form, FormStateInterface $form_state, $alert_bar_message_revision = NULL) {
    $this->revision = $this->AlertBarStorage->loadRevision($alert_bar_message_revision);
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->AlertBarStorage->deleteRevision($this->revision->getRevisionId());

    $this->logger('content')->notice('Alert Bar Message: deleted %title revision %revision.', ['%title' => $this->revision->label(), '%revision' => $this->revision->getRevisionId()]);
    $this->messenger()->addMessage(t('Revision from %revision-date of Alert Bar Message %title has been deleted.', ['%revision-date' => format_date($this->revision->getRevisionCreationTime()), '%title' => $this->revision->label()]));
    $form_state->setRedirect(
      'entity.alert_bar_message.canonical',
       ['alert_bar_message' => $this->revision->id()]
    );
    if ($this->connection->query('SELECT COUNT(DISTINCT vid) FROM {alert_bar_message_field_revision} WHERE id = :id', [':id' => $this->revision->id()])->fetchField() > 1) {
      $form_state->setRedirect(
        'entity.alert_bar_message.version_history',
         ['alert_bar_message' => $this->revision->id()]
      );
    }
  }

}
