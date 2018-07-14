<?php
/**
 * @file
 * Preprocess formatter template.
 */

/**
 * Prepares variables for views carousel template.
 *
 * Default template: views-bootstrap-carousel.html.twig.
 *
 * @param array $variables
 */
function template_preprocess_views_formatter_template(array &$variables) {
  /** @var \Drupal\views\ViewExecutable $view */
  $view = $variables['view'];
  $rows = $variables['rows'];
  /** @var \Drupal\vff\Plugin\views\style\ViewFormatterTemplate $style */
  $style = $view->style_plugin;
  $options = $style->options;
  $template_file = fft_storage_dir() . "/" . $options['template'];
  $variables['template_rendered'] = '';
  if ($options['render_type'] === 'styled') {
    $variables['template_rendered'] = fft_render($template_file, ['data' => $rows]);
  }
  else {
    $renderedField = $style->getRenderedFields();
    if (!empty($renderedField)) {
      $variables['template_rendered'] = fft_render($template_file, ['data' => $renderedField]);
    }
  }
}
