<?php

namespace Drupal\bot_event_instance_entities\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\bot_event_instance_entities\Entity\EventInstanceEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class EventInstanceEntityController.
 *
 *  Returns responses for Event Instance routes.
 */
class EventInstanceEntityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->renderer = $container->get('renderer');
    return $instance;
  }

  /**
   * Displays a Event Instance revision.
   *
   * @param int $event_instance_entity_revision
   *   The Event Instance revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($event_instance_entity_revision) {
    $event_instance_entity = $this->entityTypeManager()->getStorage('event_instance_entity')
      ->loadRevision($event_instance_entity_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('event_instance_entity');

    return $view_builder->view($event_instance_entity);
  }

  /**
   * Page title callback for a Event Instance revision.
   *
   * @param int $event_instance_entity_revision
   *   The Event Instance revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($event_instance_entity_revision) {
    $event_instance_entity = $this->entityTypeManager()->getStorage('event_instance_entity')
      ->loadRevision($event_instance_entity_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $event_instance_entity->label(),
      '%date' => $this->dateFormatter->format($event_instance_entity->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Event Instance.
   *
   * @param \Drupal\bot_event_instance_entities\Entity\EventInstanceEntityInterface $event_instance_entity
   *   A Event Instance object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(EventInstanceEntityInterface $event_instance_entity) {
    $account = $this->currentUser();
    $event_instance_entity_storage = $this->entityTypeManager()->getStorage('event_instance_entity');

    $langcode = $event_instance_entity->language()->getId();
    $langname = $event_instance_entity->language()->getName();
    $languages = $event_instance_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $event_instance_entity->label()]) : $this->t('Revisions for %title', ['%title' => $event_instance_entity->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all event instance revisions") || $account->hasPermission('administer event instance entities')));
    $delete_permission = (($account->hasPermission("delete all event instance revisions") || $account->hasPermission('administer event instance entities')));

    $rows = [];

    $vids = $event_instance_entity_storage->revisionIds($event_instance_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\bot_event_instance_entities\EventInstanceEntityInterface $revision */
      $revision = $event_instance_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $event_instance_entity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.event_instance_entity.revision', [
            'event_instance_entity' => $event_instance_entity->id(),
            'event_instance_entity_revision' => $vid,
          ]));
        }
        else {
          $link = $event_instance_entity->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.event_instance_entity.translation_revert', [
                'event_instance_entity' => $event_instance_entity->id(),
                'event_instance_entity_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.event_instance_entity.revision_revert', [
                'event_instance_entity' => $event_instance_entity->id(),
                'event_instance_entity_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.event_instance_entity.revision_delete', [
                'event_instance_entity' => $event_instance_entity->id(),
                'event_instance_entity_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['event_instance_entity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
