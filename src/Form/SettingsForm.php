<?php

/**
 * @file
 * Contains \Drupal\fft\Form\SettingsForm.
 */

namespace Drupal\fft\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures devel settings.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['fft.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'fft_admin_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['fft_store_dir'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Formmater template directory'),
      '#default_value' => $this->config('fft.settings')->get('fft_store_dir'),
      '#description' => t('Configure directory store field formatter template.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('fft.settings')
      ->set('fft_store_dir', $form_state->getValue('fft_store_dir'))
      ->save();
  }
}
