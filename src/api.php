<?php

/**
 *
 * @author            Pierre Duverneix
 * @copyright         2021 Fondation UNIT
 * @license           GPL-2.0-or-later
*/

require_once dirname(__DIR__) . '/../../../wp-load.php';
require plugin_dir_path(__FILE__) . '../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

class LearningPathApi {
    public function getFields() {
        $seeds = Yaml::parseFile(__DIR__ . '/../seeds/fields.yml');
        return $seeds;
    }

    public function getData($id) {
        $result = new \stdClass();
        $result = array();
        $files = glob(__DIR__ . '/../seeds/**/*.yml');
        
        foreach($files as $filename) {
            $parts = explode("/", $filename);
            $fileId = explode("-", $parts[count($parts)-1])[0];
            if (is_numeric($fileId) && $fileId === $id) {
                return Yaml::parseFile($filename);
            }
        }
        return $result;
    }
}
