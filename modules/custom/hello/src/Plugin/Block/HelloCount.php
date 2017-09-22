<?php

namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a hello block
 *
 * @Block(
 *  id="hello_block_count",
 *  admin_label = @Translation("Hello count")
 * )
 */

class HelloCount extends BlockBase {
  public function build() {
    $results = db_select('sessions')->fields(NULL, array('sid'))->execute()->fetchAll();
    $count = count($results);

    $database = \Drupal::database();

    $count = $database->select('sessions','s')
                      ->countQuery()
                      ->execute()
                      ->fetchField();

    $build = array(
      "#markup" => $this->t("Il y a %count comptes actifs !",array('%count' => $count)),
      "#cache" => array(
        "keys" => ["hello:count"],
        "max-age" => 10
      )
    );

    return $build;
  }
}
