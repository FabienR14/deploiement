<?php

namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a hello block
 *
 * @Block(
 *  id="hello_block",
 *  admin_label = @Translation("Hello !")
 * )
 */

class Hello extends BlockBase {
  public function build() {
    $build = array(
      "#markup" => $this->t("Bonjour !")
    );

    return $build;
  }
}
