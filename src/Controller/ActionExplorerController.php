<?php
namespace Drupal\action_explorer\Controller;
use Drupal\Core\Controller\ControllerBase;

class ActionExplorerController extends ControllerBase {

  public function all(): array {
    $actions = $this->getArr();

    return [
      '#theme' => 'all_actions',
      '#actions' => $actions
    ];
  }

  public function type(): array {
    $definitions = $this->getArr();

    $actions_by_type = [];
    foreach ($definitions as $definition) {
      $type = $definition['type'];
      $actions_by_type[$type][] = [
        'id' => $definition['id'],
        'label' => $definition['label'],
        'provider' => $definition['provider'],
        'type' => $type,
        'derived' => $definition['derived'],
      ];
    }

    return [
      '#theme' => 'actions_by_type',
      '#actions_by_type' => $actions_by_type,
    ];

  }

  public function provider() {
    $definitions = $this->getArr();


    $actions_by_provider = [];
    foreach ($definitions as $definition) {
      $provider = $definition['provider'];
      $actions_by_provider[$provider][] = [
        'id' => $definition['id'],
        'label' => $definition['label'],
        'provider' => $provider,
        'type' => $definition['type'],
        'derived' => $definition['derived'],
      ];
    }
    return [
      '#theme' => 'actions_by_provider',
      '#actions_by_provider' => $actions_by_provider,
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
        'type' => $definition['type'] ?: 'Without any type',
        'derived' => isset($definition['deriver']) ? 'Yes' : 'No',
      ];
    }
    return $actions;
  }

}
