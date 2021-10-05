<?php

/**
 *
 * @author            Pierre Duverneix
 * @copyright         2021 Fondation UNIT
 * @license           GPL-2.0-or-later
*/

require_once dirname( __DIR__ ) . '/../../wp-load.php';
require_once dirname( __DIR__ ) . '/learning-paths-api/src/controllers/diploma_controller.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );
$len = count($uri);

if ((isset($uri[$len-2]) && ($uri[$len-2] != 'diploma' && $uri[$len-2] != 'fields')) || !isset($uri[$len-1])) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

$objFeedController = new DiplomaController();
if ($uri[$len-2] == 'diploma') {
    $strMethodName = 'diplomaAction';
} else {
    $strMethodName = 'fieldsAction';
}
$objFeedController->{$strMethodName}();

print $objFeedController;
