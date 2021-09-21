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
    learningpathsapi_install();
    learningpathsapi_seed();
}

function learningpathsapi_uninstall() {

}

function learningpathsapi_install() {
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $table_name = $wpdb->prefix . "learningpathsapi_diploma"; 
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        name varchar(255) NOT NULL,
        url varchar(55) DEFAULT '',
        PRIMARY KEY (id)
    ) $charset_collate;";

    dbDelta($sql);
    unset($sql);

    $table_name = $wpdb->prefix . "learningpathsapi_year"; 
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    dbDelta($sql);
    unset($sql);

    $table_name = $wpdb->prefix . "learningpathsapi_ue"; 
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    dbDelta($sql);
    unset($sql);

    $table_name = $wpdb->prefix . "learningpathsapi_resource"; 
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        name varchar(255) NOT NULL,
        volume varchar(8) NOT NULL,
        url varchar(55) DEFAULT '' NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";
    
    dbDelta($sql);
    unset($sql);

    $table_name = $wpdb->prefix . "learningpathsapi_resourcetype"; 
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    dbDelta($sql);
    unset($sql);

    $table_name = $wpdb->prefix . "learningpathsapi_diploma_year_ue"; 
    $sql = "CREATE TABLE $table_name (
        diploma_id mediumint(9) NOT NULL,
        year_id mediumint(9) NOT NULL,
        ue_id mediumint(9) NOT NULL,
        FOREIGN KEY (diploma_id)
            REFERENCES " . $wpdb->prefix . "learningpathsapi_diploma(id)
            ON DELETE CASCADE,
        FOREIGN KEY (year_id)
            REFERENCES " . $wpdb->prefix . "learningpathsapi_year(id)
            ON DELETE CASCADE,
        FOREIGN KEY (ue_id)
            REFERENCES " . $wpdb->prefix . "learningpathsapi_ue(id)
            ON DELETE CASCADE
    ) $charset_collate;";

    dbDelta($sql);
    unset($sql);

    $table_name = $wpdb->prefix . "learningpathsapi_resource_resourcetype"; 
    $sql = "CREATE TABLE $table_name (
        resource_id mediumint(9) NOT NULL,
        resourcetype_id mediumint(9) NOT NULL,
        FOREIGN KEY (resource_id)
            REFERENCES " . $wpdb->prefix . "learningpathsapi_resource(id)
            ON DELETE CASCADE,
        FOREIGN KEY (resourcetype_id)
            REFERENCES " . $wpdb->prefix . "learningpathsapi_resourcetype(id)
            ON DELETE CASCADE
    ) $charset_collate;";

    dbDelta($sql);
    unset($sql);

    $table_name = $wpdb->prefix . "learningpathsapi_ue_resource"; 
    $sql = "CREATE TABLE $table_name (
        ue_id mediumint(9) NOT NULL,
        resource_id mediumint(9) NOT NULL,
        FOREIGN KEY (ue_id)
            REFERENCES " . $wpdb->prefix . "learningpathsapi_ue(id)
            ON DELETE CASCADE,
        FOREIGN KEY (resource_id)
            REFERENCES " . $wpdb->prefix . "learningpathsapi_resource(id)
            ON DELETE CASCADE
    ) $charset_collate;";

    dbDelta($sql);
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
