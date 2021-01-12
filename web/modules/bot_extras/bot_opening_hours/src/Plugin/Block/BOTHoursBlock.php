<?php

namespace Drupal\bot_opening_hours\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\bot_opening_hours\Entity\BOTOpeningHoursExc;
use Drupal\Core\Datetime\DrupalDateTime;

use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'BOTHoursBlock' block.
 *
 * @Block(
 *  id = "bot_hours_block",
 *  admin_label = @Translation("BOT Hours Block"),
 * )
 */
class BOTHoursBlock extends BlockBase implements BlockPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $block_config = $this->getConfiguration();

    $message_config = \Drupal::config('bot_opening_hours.botdefaulthours');
    $open_message = $message_config->get('open_message');
    $closed_message = $message_config->get('closed_message');

    $layout = $block_config['layout'];

    $build = [];
    $build['#theme'] = 'bot_hours_block';

    $now = new DrupalDateTime('now');

    $query = \Drupal::entityQuery('bot_opening_hours_exc');
    $query->condition('status', 1);
    $query->condition('field_date', $now->format(DATETIME_DATE_STORAGE_FORMAT), '=');
    $query->sort('created', 'DESC');
    $query->range(0, 1);
    $entity_ids = $query->execute();

    $exc = BOTOpeningHoursExc::loadMultiple($entity_ids);

    $time_open = FALSE;
    $time_closed = FALSE;
    $show_hours = FALSE;
    $class_status = '';
    $message = '';
    $today = $layout === 'sidebar' ? date('n/d/y') : date('F j, Y');

    //There is an exception
    if ($exc) {
      $exc = array_shift($exc);

      $hours = $exc->get('field_hours')->getValue();

      if (isset($hours[0]['to'])) {
        $time_open = date('g:iA', strtotime('midnight') + $hours[0]['to']);
      }
      if (isset($hours[0]['from'])) {
        $time_closed = date('g:iA', strtotime('midnight') + $hours[0]['from']);
      }

      $status = $exc->get('field_status')->getValue();
      $message = $exc->get('field_message')->getValue();
      $message = isset($message[0]['value']) ? $message[0]['value'] : '';

      if ($status[0]['value'] === 'open') {
        $class_status = "open";

        if (!$message) {
          $message = $open_message;
        }
      } else {
        $class_status = "closed";

        if (!$message) {
          $message = $closed_message;
        }
      }

      if ($status[0]['value'] === 'open' && $time_open && $time_closed) {
        $show_hours = TRUE;
      } else {

      }

    } else {
      $weekday = strtolower(date('D'));
      $config = \Drupal::config('bot_opening_hours.botdefaulthours');
      $info = $config->get($weekday);

      if ($info['status'] === 'open') {
        $class_status = 'open';
        $message = $open_message;
        $show_hours = TRUE;
      } else {
        $class_status = 'closed';
        $message = $closed_message;
        $show_hours = FALSE;
      }

      if (isset($info['time_open'])) {
        $time_open = date('g:iA', strtotime('midnight') + (int)$info['time_open']);
      }
      if (isset($info['time_closed'])) {
        $time_closed = date('g:iA', strtotime('midnight') + (int)$info['time_closed']);
      }

    }

    $build['#layout'] = $layout;
    $build['#today'] = $today;
    $build['#status'] = $message;
    $build['#time_open'] = $time_open;
    $build['#time_closed'] = $time_closed;
    $build['#show_hours'] = $show_hours;
    $build['#class_status'] = $class_status;


    //$build['bot_hours_block']['test'] = 'Test';
    //$build['bot_hours_block']['#markup'] = $ret;

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    $form['layout'] = [
      '#type' => 'select',
      '#title' => $this->t('Layout'),
      '#options' => ['footer' => $this->t('Footer'), 'sidebar' => $this->t('Sidebar')],
      '#default_value' => isset($config['layout']) ? $config['layout'] : 'sidebar',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();
    $this->configuration['layout'] = $values['layout'];
  }

}
