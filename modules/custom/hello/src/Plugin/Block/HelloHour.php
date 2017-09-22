<?php

namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a hello block
 *
 * @Block(
 *  id="hello_block_hour",
 *  admin_label = @Translation("Hello hour")
 * )
 */

class HelloHour extends BlockBase {
  public function build() {
    $heure = format_date(time(), 'custom', 'H:i:s');
    $heure = \Drupal::service('date.formatter')->format(REQUEST_TIME,'custom','H:i s\s');
    $build = array(
      "#markup" => $this->t("Bonjour, il est %hour !",array('%hour' => $heure)),
      "#cache" => array(
        "keys" => ["hello"],
        "max-age" => 10
      )
    );

    return $build;
  }
}
