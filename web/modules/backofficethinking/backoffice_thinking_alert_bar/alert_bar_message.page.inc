<?php

/**
 * @file
 * Contains alert_bar_message.page.inc.
 *
 * Page callback for Alert Bar Message entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Alert Bar Message templates.
 *
 * Default template: alert_bar_message.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_alert_bar_message(array &$variables) {
  // Fetch AlertBar Entity Object.
  $alert_bar_message = $variables['elements']['#alert_bar_message'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
