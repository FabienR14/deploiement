<?php

namespace Drupal\hello\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Implements a hello admin form.
 */
class HelloAdminForm extends ConfigFormBase {

  protected $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}.
   */
  public function getFormID() {
    return 'hello_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return array('hello.config');
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $color = $this->config('hello.config')->get('color');

    $form['color'] = array(
      '#type'    => 'select',
      '#title'   => $this->t('Choose a block color'),
      '#options' => array(
        ''             => $this->t('No color'),
        'blue-class'   => $this->t('Blue'),
        'yellow-class' => $this->t('Yellow'),
        'red-class'    => $this->t('Red'),
      ),
      '#default_value' => $color,
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('hello.config')
      ->set('color', $form_state->getValue('color'))
      ->save();

    $this->entityTypeManager->getViewBuilder('block')->resetCache();

    parent::submitForm($form, $form_state);
  }
}
