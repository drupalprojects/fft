<?php

/**
 * @file
 * Contains \Drupal\fft\Form\SettingsForm.
 */

namespace Drupal\fft\Form;

use Drupal\Core\Form\ConfigFormBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines a form that configures devel settings.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'fft_admin_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, array &$form_state, Request $request = NULL) {

    $form['fft_store_dir'] = array(
      '#type'          => 'textfield',
      '#title'         => t('Formmater template directory'),
      '#default_value' => $this->config('fft.settings')->get('fft_store_dir'),
      '#description'   => t('Configure directory store field formatter template.'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, array &$form_state) {
    $this->config('fft.settings')
      ->set('fft_store_dir', $form_state['values']['fft_store_dir'])
      ->save();
  }
}
