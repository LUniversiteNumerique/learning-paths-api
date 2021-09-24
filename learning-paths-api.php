<?php

/**
 * Plugin Name: Learning Paths API
 *
 * @author            Pierre Duverneix
 * @copyright         2021 Fondation UNIT
 * @license           GPL-2.0-or-later
 * Plugin URI:        https://example.com/plugin-name
 * Description:       Learning paths API of L'Université Numérique
 * Version:           1.0.0
*/

register_activation_hook(__FILE__, 'learningpathsapi_activate');
register_deactivation_hook(__FILE__, 'learningpathsapi_deactivate');
register_uninstall_hook(__FILE__, 'learningpathsapi_uninstall');

function learningpathsapi_deactivate() {
}

function learningpathsapi_activate() {
}

function learningpathsapi_install() {
}

function learningpathsapi_seed() {
}

function learningpathsapi_uninstall() {
}
