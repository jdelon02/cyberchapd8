<?php

namespace Drupal\backoffice_thinking_alert_bar\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\backoffice_thinking_alert_bar\Entity\AlertBarInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AlertBarController.
 *
 *  Returns responses for Alert Bar Message routes.
 */
class AlertBarController extends ControllerBase implements ContainerInjectionInterface {

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
   * Displays a Alert Bar Message revision.
   *
   * @param int $alert_bar_message_revision
   *   The Alert Bar Message revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($alert_bar_message_revision) {
    $alert_bar_message = $this->entityTypeManager()->getStorage('alert_bar_message')
      ->loadRevision($alert_bar_message_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('alert_bar_message');

    return $view_builder->view($alert_bar_message);
  }

  /**
   * Page title callback for a Alert Bar Message revision.
   *
   * @param int $alert_bar_message_revision
   *   The Alert Bar Message revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($alert_bar_message_revision) {
    $alert_bar_message = $this->entityTypeManager()->getStorage('alert_bar_message')
      ->loadRevision($alert_bar_message_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $alert_bar_message->label(),
      '%date' => $this->dateFormatter->format($alert_bar_message->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Alert Bar Message.
   *
   * @param \Drupal\backoffice_thinking_alert_bar\Entity\AlertBarInterface $alert_bar_message
   *   A Alert Bar Message object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(AlertBarInterface $alert_bar_message) {
    $account = $this->currentUser();
    $alert_bar_message_storage = $this->entityTypeManager()->getStorage('alert_bar_message');

    $langcode = $alert_bar_message->language()->getId();
    $langname = $alert_bar_message->language()->getName();
    $languages = $alert_bar_message->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $alert_bar_message->label()]) : $this->t('Revisions for %title', ['%title' => $alert_bar_message->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all alert bar message revisions") || $account->hasPermission('administer alert bar message entities')));
    $delete_permission = (($account->hasPermission("delete all alert bar message revisions") || $account->hasPermission('administer alert bar message entities')));

    $rows = [];

    $vids = $alert_bar_message_storage->revisionIds($alert_bar_message);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\backoffice_thinking_alert_bar\AlertBarInterface $revision */
      $revision = $alert_bar_message_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $alert_bar_message->getRevisionId()) {
          $link = $this->l($date, new Url('entity.alert_bar_message.revision', [
            'alert_bar_message' => $alert_bar_message->id(),
            'alert_bar_message_revision' => $vid,
          ]));
        }
        else {
          $link = $alert_bar_message->link($date);
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
              Url::fromRoute('entity.alert_bar_message.translation_revert', [
                'alert_bar_message' => $alert_bar_message->id(),
                'alert_bar_message_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.alert_bar_message.revision_revert', [
                'alert_bar_message' => $alert_bar_message->id(),
                'alert_bar_message_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.alert_bar_message.revision_delete', [
                'alert_bar_message' => $alert_bar_message->id(),
                'alert_bar_message_revision' => $vid,
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

    $build['alert_bar_message_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
