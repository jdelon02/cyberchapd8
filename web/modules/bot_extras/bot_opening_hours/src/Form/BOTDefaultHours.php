<?php

namespace Drupal\bot_opening_hours\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class BOTDefaultHours.
 */
class BOTDefaultHours extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'bot_opening_hours.botdefaulthours',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bot_default_hours';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('bot_opening_hours.botdefaulthours');
    $days = [
      'sun' => 'Sunday',
      'mon' => 'Monday',
      'tue' => 'Tuesday',
      'wed' => 'Wednesday',
      'thu' => 'Thursday',
      'fri' => 'Friday',
      'sat' => 'Saturday',
    ];

    $form['open_message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Default Open Message Preamble Text'),
      '#description' => $this->t('Leave blank if not necessary.'),
      '#default_value' => $config->get('open_message') ? $config->get('open_message') : 'Open',
    ];

    $form['closed_message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Default Closed Text'),
      '#description' => $this->t('Leave blank if not necessary.'),
      '#default_value' => $config->get('closed_message') ? $config->get('closed_message') : 'Closed',
    ];
    
    $form['#tree'] = TRUE;
    
    foreach ($days as $key => $day) {

      $form[$key] = [
        '#type' => 'fieldset',
        '#title' => $day,
      ];
      $form[$key]['time_open'] = [
        '#type' => 'time',
        '#title' => $this->t('Open Time'),
        '#default_value' => is_integer($config->get($key)['time_open']) ? date('H:i', strtotime('midnight') + $config->get($key)['time_open']) : '',
      ];
      $form[$key]['time_closed'] = [
        '#type' => 'time',
        '#title' => $this->t('Close Time'),
        '#default_value' => is_integer($config->get($key)['time_closed']) ? date('H:i', strtotime('midnight') + $config->get($key)['time_closed']) : '',
      ];
      $form[$key]['status'] = [
        '#type' => 'select',
        '#title' => $this->t('Open/Closed'),
        '#options' => ['open' => $this->t('Open'), 'closed' => $this->t('Closed')],
        '#size' => 1,
        '#default_value' => $config->get($key)['status'] ? $config->get($key)['status'] : 'closed',
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $days = [
      'sun' => 'Sunday',
      'mon' => 'Monday',
      'tue' => 'Tuesday',
      'wed' => 'Wednesday',
      'thu' => 'Thursday',
      'fri' => 'Friday',
      'sat' => 'Saturday',
    ];

    $this->config('bot_opening_hours.botdefaulthours')
      ->set('open_message', $form_state->getValue('open_message'))->save();
    $this->config('bot_opening_hours.botdefaulthours')
      ->set('closed_message', $form_state->getValue('closed_message'))->save();   
 
    foreach ($days as $key => $day) {
      $this->config('bot_opening_hours.botdefaulthours')
        ->set($key, [
          'time_open' => $form_state->getValue($key)['time_open'],
          'time_closed' => $form_state->getValue($key)['time_closed'],
          'status' => $form_state->getValue($key)['status'],
        ])
        ->save();
    }

  }

}
