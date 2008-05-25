<?php
//$Id $
/**
 * @file views_json.views.inc 
 * Views style plugin to render nodes in the JSON data format.
 * @see views-view-json.tpl.php views-view-row-unformatted.tpl.php
 * @ingroup views_plugins
 */

/**
 * Implementation of hook_views_plugin
 *
 */
function views_json_views_plugins() {
  return array(
    'style' => array( //declare the views_json style plugin
      'views_json' => array(
        'title' => t('JSON data document'),
        'theme' => 'views_view_json',
        'help' => t('Displays nodes in the JSON data format.'),
        'handler' => 'views_plugin_style_json',
        'uses row plugin' => TRUE,
        'uses fields' => TRUE,
        'uses options' => TRUE,
        'type' => 'normal',       
      ),
    ),
    'row' => array( //declare the unformatted row plugin
      'unformatted' => array(
        'title' => t('Unformatted'),
        'help' => t('(Displays the unformatted data for each row from the views query with each row on a new line. Set as | for views_json.'),
        'handler' => 'views_plugin_row_unformatted',
        'theme' => 'views_view_row_unformatted',
        'uses fields' => TRUE,
        'uses options' => TRUE,
        'type' => 'normal',
      )
     )  
  );
}

/**
 * Implementation of views_plugin_style
 *
 */

class views_plugin_style_json extends views_plugin_style {
  
  /**
   * Set default options
   */
  function options(&$options) {
    $options['format'] = 'Exhibit';
  }
  
  /**
   * Provide a form for setting options.
   *
   * @param array $form
   * @param array $form_state
   */  
  function options_form(&$form, &$form_state) {
    $form['format'] = array(
      '#type' => 'radios',
      '#title' => t('JSON data format'),
      '#options' => array('Exhibit' => t('MIT Simile/Exhibit'), 'Canonical' => t('Canonical'), 'JSONP' => t('JSONP')),
      '#default_value' => $this->options['format'],
    );
  }

}

/**
 * Theme preprocess function for views-view-json.tpl.php
 *
 * @param array $vars
 */
function template_preprocess_views_view_json(&$vars) {
  $view     = &$vars['view'];
  $options  = $view->style_handler->options;
  $handler  = $view->style_handler;
}


/**
 * Implementation of views_row_plugin
 *
 */
class views_plugin_row_unformatted extends views_plugin_row {
	
	/**
	 * Set default options
	 *
	 * @param array $options
	 */
  function options(&$options) {
    $options['separator'] = '|';
  }

  /**
   * Provide a form for setting options.
   */
  function options_form(&$form, &$form_state) {
    $fields = $this->display->handler->get_option('fields');
    $options = array();
    foreach ($fields as $field => $info) {
      $handler = views_get_handler($info['table'], $info['field'], 'field');
      if ($handler) {
        $options[$field] = $handler->ui_name();
      }
    }
  
    $form['separator'] = array(
      '#title' => t('Separator'),
      '#type' => 'textfield',
      '#size' => 10,
      '#default_value' => isset($this->options['separator']) ? $this->options['separator'] : ',',
      '#description' => t('The separator is placed between fields.'),
    );
  }
  
}

/**
 * Theme preprocess function for views-view-row-unformatted.tpl.php
 */
function template_preprocess_views_view_row_unformatted(&$vars) {
  $view = $vars['view'];
  //print('preprocess');
  // Loop through the fields for this view.
  foreach ($view->field as $id => $field) {
    if (!empty($field['handler']) && is_object($field['handler'])) {
      $object = new stdClass();
      $object->content = $field['handler']->theme($vars['row']);
      if (isset($field['handler']->field_alias) && isset($vars['row']->{$field['handler']->field_alias})) {
        $object->raw = $vars['row']->{$field['handler']->field_alias};
      }
      else {
        $object->raw = NULL; // make sure it exists to reduce NOTICE
      }
      if (!empty($vars['options']['separator']) && $object->content) {
        $object->separator = filter_xss_admin($vars['options']['separator']);
      }

      $object->handler = $field['handler'];
      $object->class = views_css_safe($id);
      $object->label = check_plain($field['handler']->label());
      $vars['fields'][$id] = $object;
    }
  }
  
} 
