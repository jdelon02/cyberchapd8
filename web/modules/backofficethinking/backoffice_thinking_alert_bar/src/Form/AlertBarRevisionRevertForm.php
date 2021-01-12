<?php

namespace Drupal\backoffice_thinking_alert_bar\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\backoffice_thinking_alert_bar\Entity\AlertBarInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for reverting a Alert Bar Message revision.
 *
 * @ingroup backoffice_thinking_alert_bar
 */
class AlertBarRevisionRevertForm extends ConfirmFormBase {

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
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->alertBarStorage = $container->get('entity_type.manager')->getStorage('alert_bar_message');
    $instance->dateFormatter = $container->get('date.formatter');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'alert_bar_message_revision_revert_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to revert to the revision from %revision-date?', [
      '%revision-date' => $this->dateFormatter->format($this->revision->getRevisionCreationTime()),
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
    return $this->t('Revert');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return '';
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
    // The revision timestamp will be updated when the revision is saved. Keep
    // the original one for the confirmation message.
    $original_revision_timestamp = $this->revision->getRevisionCreationTime();

    $this->revision = $this->prepareRevertedRevision($this->revision, $form_state);
    $this->revision->revision_log = $this->t('Copy of the revision from %date.', [
      '%date' => $this->dateFormatter->format($original_revision_timestamp),
    ]);
    $this->revision->save();

    $this->logger('content')->notice('Alert Bar Message: reverted %title revision %revision.', ['%title' => $this->revision->label(), '%revision' => $this->revision->getRevisionId()]);
    $this->messenger()->addMessage(t('Alert Bar Message %title has been reverted to the revision from %revision-date.', ['%title' => $this->revision->label(), '%revision-date' => $this->dateFormatter->format($original_revision_timestamp)]));
    $form_state->setRedirect(
      'entity.alert_bar_message.version_history',
      ['alert_bar_message' => $this->revision->id()]
    );
  }

  /**
   * Prepares a revision to be reverted.
   *
   * @param \Drupal\backoffice_thinking_alert_bar\Entity\AlertBarInterface $revision
   *   The revision to be reverted.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return \Drupal\backoffice_thinking_alert_bar\Entity\AlertBarInterface
   *   The prepared revision ready to be stored.
   */
  protected function prepareRevertedRevision(AlertBarInterface $revision, FormStateInterface $form_state) {
    $revision->setNewRevision();
    $revision->isDefaultRevision(TRUE);
    $revision->setRevisionCreationTime(REQUEST_TIME);

    return $revision;
  }

}
