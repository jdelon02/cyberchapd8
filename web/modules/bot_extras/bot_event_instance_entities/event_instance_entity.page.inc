<?php

/**
 * @file
 * Contains event_instance_entity.page.inc.
 *
 * Page callback for Event Instance entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Event Instance templates.
 *
 * Default template: event_instance_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_event_instance_entity(array &$variables) {
  // Fetch EventInstanceEntity Entity Object.
  $event_instance_entity = $variables['elements']['#event_instance_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
