<?php

namespace Drupal\vff\Plugin\views\style;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\style\StylePluginBase;

/**
 * Style plugin to render each item in an ordered or unordered list.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "views_formatter_template",
 *   title = @Translation("View Formatter Template"),
 *   help = @Translation("Displays rows in a Bootstrap Grid layout"),
 *   theme = "views_formatter_template",
 *   theme_file = "../vff.theme.inc",
 *   display_types = {"normal"}
 * )
 */
class ViewFormatterTemplate extends StylePluginBase {
  /**
   * Overrides \Drupal\views\Plugin\views\style\StylePluginBase::usesRowPlugin.
   *
   * @var bool
   */
  protected $usesRowPlugin = TRUE;

  /**
   * Overrides \Drupal\views\Plugin\views\style\StylePluginBase::usesRowClass.
   *
   * @var bool
   */
  protected $usesRowClass = TRUE;

  /**
   * Does the style plugin support grouping of rows.
   *
   * @var bool
   */
  protected $usesGrouping = FALSE;

  /**
   * Definition.
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['fft_template'] = ['default' => ''];
    $options['render_type'] = array('default' => 'raw');
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    $fft_templates = fft_get_templates('views');

    $form['template'] = [
      '#title' => $this->t('Template'),
      '#type' => 'select',
      '#options' => $fft_templates['templates'],
      '#default_value' => $this->options['fft_template'],
      '#attributes' => ['class' => ['fft-template']],
    ];

    $form['render_type'] = [
      '#title' => $this->t('Field Render Format'),
      '#type' => 'select',
      '#options' => [
        'raw' => 'Raw',
        'styled' => 'Styled',
      ],
      '#default_value' => $this->options['render_type'],
      '#description' => $this->t('Select field render format.'),
    ];

    $field_labels = $this->displayHandler->getFieldLabels(TRUE);
    $form['fields_available'] = [
      '#type' => 'item',
      '#title' => $this->t('Fields available for Twig template'),
      '#markup' => json_encode($field_labels),
      '#states' => [
        'visible' => [
          ':input[name="style_options[render_type]"]' => ['value' => 'raw'],
        ],
      ],
    ];

    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * Get rendered fields.
   *
   * @return array|null
   */
  public function getRenderedFields() {
    return $this->rendered_fields;
  }
}
