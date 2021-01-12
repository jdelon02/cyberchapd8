<?php

/**
 * @file
 * Contains bot_opening_hours_exc.page.inc.
 *
 * Page callback for BOT Opening Hours Exception entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for BOT Opening Hours Exception templates.
 *
 * Default template: bot_opening_hours_exc.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_bot_opening_hours_exc(array &$variables) {
  // Fetch BOTOpeningHoursExc Entity Object.
  $bot_opening_hours_exc = $variables['elements']['#bot_opening_hours_exc'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
