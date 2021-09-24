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

require_once dirname( __DIR__ ) . '/../../wp-load.php';
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

class LPASchema {
    public function __construct($table_prefix, $charset_collate) {
        $this->table_prefix = $table_prefix;
        $this->charset_collate = $charset_collate;
    }

    public function build_tables() {
        $tables = array();

        $table_name = $this->table_prefix . "learningpathsapi_diploma"; 
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            name varchar(255) NOT NULL,
            url varchar(55) DEFAULT '',
            PRIMARY KEY (id)
        ) $this->charset_collate;";
        array_push($tables, $sql);
        unset($sql);

        $table_name = $this->table_prefix . "learningpathsapi_year"; 
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            PRIMARY KEY (id)
        ) $this->charset_collate;";
        array_push($tables, $sql);
        unset($sql);

        $table_name = $this->table_prefix . "learningpathsapi_ue"; 
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            PRIMARY KEY (id)
        ) $this->charset_collate;";
        array_push($tables, $sql);
        unset($sql);

        $table_name = $this->table_prefix . "learningpathsapi_resource"; 
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            name varchar(255) NOT NULL,
            volume varchar(8) NOT NULL,
            url varchar(55) DEFAULT '' NOT NULL,
            PRIMARY KEY (id)
        ) $this->charset_collate;";
        array_push($tables, $sql);
        unset($sql);

        $table_name = $this->table_prefix . "learningpathsapi_resourcetype"; 
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            PRIMARY KEY (id)
        ) $this->charset_collate;";
        array_push($tables, $sql);
        unset($sql);

        $table_name = $this->table_prefix . "learningpathsapi_diploma_year_ue"; 
        $sql = "CREATE TABLE $table_name (
            diploma_id mediumint(9) NOT NULL,
            year_id mediumint(9) NOT NULL,
            ue_id mediumint(9) NOT NULL,
            FOREIGN KEY (diploma_id)
                REFERENCES " . $this->table_prefix . "learningpathsapi_diploma(id)
                ON DELETE CASCADE,
            FOREIGN KEY (year_id)
                REFERENCES " . $this->table_prefix . "learningpathsapi_year(id)
                ON DELETE CASCADE,
            FOREIGN KEY (ue_id)
                REFERENCES " . $this->table_prefix . "learningpathsapi_ue(id)
                ON DELETE CASCADE
        ) $this->charset_collate;";
        array_push($tables, $sql);
        unset($sql);

        $table_name = $this->table_prefix . "learningpathsapi_resource_resourcetype"; 
        $sql = "CREATE TABLE $table_name (
            resource_id mediumint(9) NOT NULL,
            resourcetype_id mediumint(9) NOT NULL,
            FOREIGN KEY (resource_id)
                REFERENCES " . $this->table_prefix . "learningpathsapi_resource(id)
                ON DELETE CASCADE,
            FOREIGN KEY (resourcetype_id)
                REFERENCES " . $this->table_prefix . "learningpathsapi_resourcetype(id)
                ON DELETE CASCADE
        ) $this->charset_collate;";
        array_push($tables, $sql);
        unset($sql);

        $table_name = $this->table_prefix . "learningpathsapi_ue_resource"; 
        $sql = "CREATE TABLE $table_name (
            ue_id mediumint(9) NOT NULL,
            resource_id mediumint(9) NOT NULL,
            FOREIGN KEY (ue_id)
                REFERENCES " . $this->table_prefix . "learningpathsapi_ue(id)
                ON DELETE CASCADE,
            FOREIGN KEY (resource_id)
                REFERENCES " . $this->table_prefix . "learningpathsapi_resource(id)
                ON DELETE CASCADE
        ) $this->charset_collate;";
        array_push($tables, $sql);
        unset($sql);

        return $tables;
    }
};