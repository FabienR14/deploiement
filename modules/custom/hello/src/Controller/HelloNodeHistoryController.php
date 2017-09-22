<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

class HelloNodeHistoryController extends ControllerBase {

  public function content($node) {
    $param = $node;

    $article = $this->entityTypeManager()->getStorage('node')->load($param);

    $database = \Drupal::database();

    $history = $database->select('hello_node_history','h')
                        ->fields('h',
                          array(
                            'uid',
                            'update_time'
                          ))
                        ->condition("nid",$param)
                        ->execute();

    $history = $history->fetchAll();

    $rows = array();
    $userStorage = $this->entityTypeManager()->getStorage('user');
    $dateFormatter = \Drupal::service('date.formatter');

    foreach ($history as $ligne) {
      $user = $userStorage->load($ligne->uid)->toLink();
      $date = $dateFormatter->format($ligne->update_time);
      $rows[] = array($user,$date);
    }

    $message = t("Voici l'historique du node %param",array('%param' => $param));

    $table = array(
      '#theme' => 'table',
      '#header' => array($this->t('Author'),$this->t('Update time')),
      '#rows' => $rows
    );

    $pager = array('#type' => 'pager');

    $build = array(
      'table' => $table,
      'pager' => $pager,
      '#cache' => array(
        'keys' => ['hello_node_history_pager:'.$param],
        'tags' => ['node:'.$param],
        'contexts' => ['url'],
      )
    );

    return $build;
  }

  public function contentTwig($node) {
    $param = $node;

    $article = $this->entityTypeManager()->getStorage('node')->load($param);

    $database = \Drupal::database();

    $history = $database->select('hello_node_history','h')
                        ->fields('h',
                          array(
                            'uid',
                            'update_time'
                          ))
                        ->condition("nid",$param)
                        ->execute();

    $history = $history->fetchAll();

    $rows = array();
    $userStorage = $this->entityTypeManager()->getStorage('user');
    $dateFormatter = \Drupal::service('date.formatter');

    foreach ($history as $ligne) {
      $user = $userStorage->load($ligne->uid)->toLink();
      $date = $dateFormatter->format($ligne->update_time);
      $rows[] = array($user,$date);
    }

    $message = t("Voici l'historique du node %param",array('%param' => $param));

    $table = array(
      '#theme' => 'table',
      '#header' => array($this->t('Author'),$this->t('Update time')),
      '#rows' => $rows
    );

    $message = array(
      '#theme' => 'hello_node_history',
      '#data' => "René la Taupe est une souris."
    );

    $pager = array('#type' => 'pager');

    $build = array(
      'table' => $table,
      'pager' => $pager,
      'message' => $message,
      '#cache' => array(
        'keys' => ['hello_node_history_pager:'.$param],
        'tags' => ['node:'.$param],
        'contexts' => ['url'],
      )
    );

    return $build;
  }

}
