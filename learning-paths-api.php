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

require_once dirname( __DIR__ ) . '/learning-paths-api/schema.php';

register_activation_hook(__FILE__, 'learningpathsapi_activate');
register_deactivation_hook(__FILE__, 'learningpathsapi_deactivate');
register_uninstall_hook(__FILE__, 'learningpathsapi_uninstall');

function learningpathsapi_deactivate() {

}

function learningpathsapi_activate() {
    learningpathsapi_install();
    learningpathsapi_seed();
}

function learningpathsapi_uninstall() {

}

function learningpathsapi_install() {
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $lpaSchema = new LPASchema($wpdb->prefix, $charset_collate);

    foreach($lpaSchema->build_tables() as $table) {
        dbDelta($table);
    }
}

function learningpathsapi_seed() {
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    global $wpdb;

    $table_name = $wpdb->prefix . "learningpathsapi_diploma"; 
    $wpdb->insert( 
        $table_name, 
        array( 
            'time' => current_time('mysql'), 
            'name' => "Licence mention informatique"
        )
    );

    $table_name = $wpdb->prefix . "learningpathsapi_year"; 
    $wpdb->insert($table_name, array('name' => "1ère année"));

    $table_name = $wpdb->prefix . "learningpathsapi_ue"; 
    $wpdb->insert($table_name, array(
        'name' => "Algorithmique et programmation"
    ));
}
