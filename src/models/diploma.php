<?php

/**
 *
 * @author            Pierre Duverneix
 * @copyright         2021 Fondation UNIT
 * @license           GPL-2.0-or-later
*/

require_once dirname( __DIR__ ) . '/../../../../wp-load.php';

use Symfony\Component\Yaml\Yaml;

class Diploma {
    public function getData() {
        $seeds = Yaml::parseFile(__DIR__ . '/../../seeds.yml');
        $result = new \stdClass();
        $result->fields = $seeds['fields'];
        return $result;
    }
}
