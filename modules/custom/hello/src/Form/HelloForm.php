<?php

namespace Drupal\hello\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;

class HelloForm extends FormBase {

  /**
   * {@inheritdoc}
   */
   public function getFormID() {
     return 'hello_form';
   }

   /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state) {
      $form['valeur1'] = [
        '#type' => 'number',
        '#title' => t('Value 1'),
        '#description' => t('First value'),
        '#required' => TRUE,
        '#ajax' => array(
          'callback'  => array($this, 'AjaxValidateNumeric'),
          'event'     => 'keyup'
        ),
        '#prefix' => '<span id="error-message-valeur1"></span>',
      ];
      $form['operator'] = [
        '#type' => 'select',
        '#title' => $this->t('Select element'),
        '#options' => [
          '+' => $this->t('+'),
          '-' => $this->t('-'),
          '*' => $this->t('x'),
          '/' => $this->t('/'),
          '%' => $this->t('%'),
        ],
      ];
      $form['valeur2'] = [
        '#type' => 'number',
        '#title' => t('Value 2'),
        '#description' => t('First value'),
        '#required' => TRUE,
        '#ajax' => array(
          'callback'  => array($this, 'AjaxValidateNumeric'),
          'event'     => 'keyup'
        ),
        '#prefix' => '<span id="error-message-valeur2"></span>',
      ];
      $form['submit'] = [
        '#type' => 'submit',
        '#value' => t('Soumettre'),
      ];

      $table = array(
        '#theme' => 'table',
        '#header' => array($this->t('Author'),$this->t('Update time')),
        '#rows' => $rows
      );

      // $resultat = $_GET['result'];

      if ($form_state->getValue('result')) {
        $resultat = $form_state->getValue('result');
      }

      if (!isset($resultat)) $resultat = "";

      $message = t("Le résultat de votre opération est : %resultat .",array('%resultat' => $resultat));

      $build = array(
        'form' => $form,
        '#markup' => $message
      );

      return $build;
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
      $valeur1 = $form_state->getValue('valeur1');
      $valeur2 = $form_state->getValue('valeur2');
      $operator = $form_state->getValue('operator');

      if (!is_numeric($valeur1)) {
        $form_state->setErrorByName('value1', t('It must be numeric !'));
      }

      if (!is_numeric($valeur2)) {
        $form_state->setErrorByName('valeur2', t('It must be numeric !'));
      }

      if ($operator == "/" && $valeur2 == 0) {
        $form_state->setErrorByName('operator', t('division by 0 is forbidden !'));
      }
    }

    /**
     * {@inheritdoc}
     */
     public function submitForm(array &$form, FormStateInterface $form_state) {
       $valeur1 = $form_state->getValue('valeur1');
       $valeur2 = $form_state->getValue('valeur2');
       $operator = $form_state->getValue('operator');

       $result = null;
       if ($operator == "+") $result = $valeur1+$valeur2;
       if ($operator == "-") $result = $valeur1-$valeur2;
       if ($operator == "*") $result = $valeur1*$valeur2;
       if ($operator == "/") $result = $valeur1/$valeur2;
       if ($operator == "%") $result = $valeur1%$valeur2;

      //  $form_state->setRedirect('hello.hello.calculator',array('result' => $result));
      $form_state->setValue('result',$result);

      $form_state->setRebuild();
     }

     public function AjaxValidateNumeric(array &$form, FormStateInterface $form_state) {
        $response = new AjaxResponse();

        $field = $form_state->getTriggeringElement()['#name'];
        $css = ['border' => '2px solid green'];
        $message = $this->t('OK!');
        if (!is_numeric($form_state->getValue($field))) {
          $css = ['border' => '2px solid red'];
          $message = $this->t('%field must be numeric!', array('%field' => $form[$field]['#title']));
        }

        $response->AddCommand(new CssCommand("[name=$field]", $css));
        $response->AddCommand(new HtmlCommand('#error-message-' . $field, $message));

        return $response;
     }
}
