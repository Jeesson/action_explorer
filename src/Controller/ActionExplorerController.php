<?php
namespace Drupal\action_explorer\Controller;
use Drupal\Core\Controller\ControllerBase;

class ActionExplorerController extends ControllerBase {

  public function all(): array {
    $actions = $this->getArr();

    return [
      '#type' => 'table',
      '#header' => [
        'col0' => [
          'data' => $this->t('id'),
          'class' => 'header',
        ],
        'col1' => [
          'data' => $this->t('label'),
          'class' => 'header',
        ],
        'col2' => [
          'data' => $this->t('provider'),
          'class' => 'header',
        ],
        'col3' => [
          'data' => $this->t('type'),
          'class' => 'header',
        ],
        'col4' => [
          'data' => $this->t('derived'),
          'class' => 'header',
        ],
      ],
      '#rows' => $actions,
    ];
  }

  public function type(): array {
    $definitions = $this->getArr();

    $actions = [];
    foreach ($definitions as $definition) {
      $actions[] = [
        'id' => $definition['id'],
        'label' => $definition['label'],
        'provider' => $definition['provider'],
        'type' => $definition['type'],
        'derived' => isset($definition['derivative_of']) ? 'Yes' : 'No',
      ];
    }

    // Группировка элементов массива $actions по типу
    $groupedActions = [];
    foreach ($actions as $action) {
      $groupedActions[$action['type']][] = $action;
    }

    // Создание массива таблиц по типу
    $tables = [];
    foreach ($groupedActions as $type => $actionsByType) {
      $rows = [];
      foreach ($actionsByType as $action) {
        $rows[] = [
          $action['id'],
          $action['label'],
          $action['provider'],
          //$action['type'],
          $action['derived'],
        ];
      }

      $tables[] = [
        '#type' => 'table',
        //'#header' => ['ID', 'Label', 'Provider', 'Type' 'Derived'],
        '#header' => ['ID', 'Label', 'Provider', 'Derived'],
        '#rows' => $rows,
        '#caption' => "Type: {$type}",
      ];
    }

    return [
      '#theme' => 'item_list',
      '#items' => $tables,
    ];

  }

  public function provider() {
    $definitions = $this->getArr();

    $actions = [];
    foreach ($definitions as $definition) {
      $actions[] = [
        'id' => $definition['id'],
        'label' => $definition['label'],
        'provider' => $definition['provider'],
        'type' => $definition['type'],
        'derived' => isset($definition['derivative_of']) ? 'Yes' : 'No',
      ];
    }

    // Группировка элементов массива $actions по провайдеру
    $groupedActions = [];
    foreach ($actions as $action) {
      $groupedActions[$action['provider']][] = $action;
    }

    // Создание массива таблиц
    $tables = [];
    foreach ($groupedActions as $provider => $actionsByType) {
      $rows = [];
      foreach ($actionsByType as $action) {
        $rows[] = [
          $action['id'],
          $action['label'],
          //$action['provider'],
          $action['type'],
          $action['derived'],
        ];
      }

      $tables[] = [
        '#type' => 'table',
        //'#header' => ['ID', 'Label', 'Provider', 'Type' 'Derived'],
        '#header' => ['ID', 'Label', 'Type', 'Derived'],
        '#rows' => $rows,
        '#caption' => "Type: {$provider}",
      ];
    }

    return [
      '#theme' => 'item_list',
      '#items' => $tables,
    ];
  }

  /**
   * @return array
   */
  private function getArr(): array {
    $manager = \Drupal::service('plugin.manager.action');
    $definitions = $manager->getDefinitions();

    $actions = [];
    foreach ($definitions as $definition) {
      $actions[] = [
        'id' => $definition['id'],
        'label' => $definition['label'],
        'provider' => $definition['provider'],
        'type' => $definition['type'],
        'derived' => isset($definition['derivative_of']) ? 'Yes' : 'No',
      ];
    }
    return $actions;
  }

}
