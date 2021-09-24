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
    $charset_collate = $wpdb->get_charset_collate();
    $lpaSchema = new LPASchema($wpdb->prefix, $charset_collate);

    foreach($lpaSchema->seed() as $table) {
        $wpdb->insert($table->table, $table->data);
    }
}

function learningpathsapi_uninstall() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $lpaSchema = new LPASchema($wpdb->prefix, $charset_collate);
    foreach($lpaSchema->drop_tables() as $sql) {
        $wpdb->query($sql);
    }
}
