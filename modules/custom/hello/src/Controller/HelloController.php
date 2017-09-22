<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class HelloController extends ControllerBase {

  public function content() {

    $user = $this->currentUser()->getDisplayName();

    $message = $this->t('Hi %name !',array('%name' => $user));

    $build = array(
      '#markup' => $message
    );
    return $build;
  }

  public function contentWithParam($param) {

    $user = $this->currentUser()->getDisplayName();

    $message = $this->t("Hi %name, it is page $param !",array('%name' => $user));

    $build = array(
      '#markup' => $message
    );
    return $build;
  }

  public function contentRSS() {

    $response = new Response();
    $response->headers->set('Content-Type', 'application/json');
    $response->setContent(json_encode(array('Riri' => 1, 'Fifi' => 2, 'Loulou' => 3)));

    $response = new JsonResponse(array('Riri' => 1, 'Fifi' => 2, 'Loulou' => 3));

    return $response;
  }

  public function contentDisplay($start) {

    $articles = $this->entityTypeManager()->getStorage('node')->getQuery();
    if ($start) {
      $articles = $articles->condition('type',$start);
    }
    $nids = $articles->pager(10)->execute();

    $articles = $this->entityTypeManager()->getStorage('node')->loadMultiple($nids);
    $items = [];

    foreach ($articles as $key => $article) {
      $items[] = $article->toLink();
    }

    $list = array(
      '#theme' => 'item_list',
      '#items' => $items
    );

    $pager = array(
      '#type' => 'pager',
    );

    $build = array(
      'list' => $list,
      'pager' => $pager,
      '#cache' => [
        'keys' => ['hello_node_list_pager'.$start],
        'tags' => ['node_list'],
        'contexts' => ['url']
        ]
    );

    return $build;
  }

}
