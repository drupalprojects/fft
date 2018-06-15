<?php

/**
 * @file
 * Contains \Drupal\fft\Plugin\field\formatter\FFTFormatter.
 */

namespace Drupal\fft\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceFormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'fft_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "fft_formatter",
 *   label = @Translation("Formatter Template"),
 *   field_types = {
 *     "text",
 *     "text_long",
 *     "text_with_summary",
 *   },
 * )
 */
class FFTFormatter extends EntityReferenceFormatterBase {

  private static function isReference($type) {
    return substr($type, 0, 16) === 'entity_reference';
  }
  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
        'template' => '',
        'image_style_1' => '',
        'image_style_2' => '',
        'settings' => '',
      ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $settings = $this->getSettings();
    $field['type'] = $this->fieldDefinition->getType();
    $fft_templates = fft_get_templates();
    $optionsets = $fft_templates['templates'];
    $fft_templates['settings'][$settings['template']] = $settings['settings'];
    $form['#attached']['js'][] = fft_realpath('{module-fft}/fft.js');
    $form['#attached']['js'][] = [
      'data' => [
        'fft' => $fft_templates['settings'],
      ],
      'type' => 'setting',
    ];

    $form['template'] = [
      '#title' => t('Template'),
      '#type' => 'select',
      '#options' => $optionsets,
      '#default_value' => $settings['template'],
      '#attributes' => ['class' => ['fft-template']],
    ];

    switch ($field['type']) {
      case 'image':
        //$image_styles = image_styles();
        $image_style_options = image_style_options();
        $form['image_style_1'] = [
          '#type' => 'select',
          '#title' => t('Image Styles 1'),
          '#options' => $image_style_options,
          '#default_value' => $settings['image_style_1'],
        ];

        $form['image_style_2'] = [
          '#type' => 'select',
          '#title' => t('Image Styles 2'),
          '#options' => $image_style_options,
          '#default_value' => $settings['image_style_2'],
        ];
        break;
    }
    $settings_des[] = t('Add settings extras for template, one setting per line with syntax key = value.');
    $settings_des[] = t('Support array like key[] = value or key[name] = value.');
    $settings_des[] = t('Support add css and js with syntax css = pathtofile.css and js = pathtofile.css');
    $settings_des[] = t('Add multi css js with syntax css[] = pathtofile1.css, css[] = pathtofile2.css');
    $settings_des[] = t('Support path tokens:');
    $settings_des[] = t('-- <strong>{fft}</strong>: path to folder of selected template');
    $settings_des[] = t('-- <strong>{module-name}</strong>: path to folder of module "name"');
    $settings_des[] = t('-- <strong>{theme-name}</strong>: path to folder of theme "name"');
    $settings_des[] = t('-- <strong>{theme}</strong>: path to folder of current default theme');

    $form['settings'] = [
      '#type' => 'textarea',
      '#title' => t('Settings Extras'),
      '#default_value' => $settings['settings'],
      '#attributes' => ['class' => ['fft-settings']],
    ];

    $form['settings_des'] = [
      '#type' => 'fieldset',
      '#title' => t('More Information'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];

    $form['settings_des']['info'] = [
      '#type' => 'markup',
      '#markup' => nl2br(implode("\r\n", $settings_des)),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = t('Formatter Template');
    if ($this->getSetting('template') != '') {
      $fft_template = fft_get_templates();
      foreach ($fft_template['templates'] as $name => $title) {
        if ($this->getSetting('template') == $name) {
          $summary = [];
          $summary[] = t('Formatter Template: @template', ['@template' => $title]);
          break;
        }
      }
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   *
   * Loads the entities referenced in that field across all the entities being
   * viewed.
   */
  public function prepareView(array $entities_items) {
    $field_type = $this->fieldDefinition->getType();
    if (!$this->isReference($field_type)) {
      return;
    }
    parent::prepareView($entities_items);
  }


  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $entity = $items->getEntity();
    $field_type = $this->fieldDefinition->getType();
    $settings = $this->getSettings();
    $data_items = $items;
    if ($this->isReference($field_type)) {
      $data_items = $this->getEntitiesToView($items, $langcode);
    }
    $elements = fft_field_formatter_render($entity, $field_type, $data_items, $settings, $langcode);
    return $elements;
  }
}
