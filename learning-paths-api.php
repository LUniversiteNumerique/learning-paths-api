<?php

/**
 * Plugin Name: Learning Paths API
 *
 * @author            Pierre Duverneix
 * @copyright         2022 Fondation UNIT
 * @license           GPL-2.0-or-later
 * Plugin URI:        https://example.com/plugin-name
 * Description:       Learning paths API of L'Université Numérique
 * Version:           1.0.0
*/

require_once 'src/models/diploma.php';

register_activation_hook(__FILE__, 'learningpathsapi_activate');
register_deactivation_hook(__FILE__, 'learningpathsapi_deactivate');
register_uninstall_hook(__FILE__, 'learningpathsapi_uninstall');

function learningpathsapi_get_fields() {
  $diplomaModel = new Diploma();
  return $diplomaModel->getFields();
}

function learningpathsapi_get_data($data) {
  $diplomaId = $data['id'];
  $diplomaModel = new Diploma();
  return $diplomaModel->getData($diplomaId);
}

add_action('rest_api_init', function () {
  register_rest_route('learningpathsapi/v1', '/fields/all', array(
    'methods' => 'GET',
    'callback' => 'learningpathsapi_get_fields',
  ));
  register_rest_route('learningpathsapi/v1', '/data/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'learningpathsapi_get_data',
    'args' => array(
      'id' => array(
        'validate_callback' => function($param, $request, $key) {
          return is_numeric($param);
        }
      ),
    ),
  ));
});
