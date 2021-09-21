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

require_once dirname( __DIR__ ) . '/controllers/base_controller.php';
require_once dirname( __DIR__ ) . '/models/diploma.php';

class DiplomaController extends BaseController {
    /**
     * "/diploma/%id" Endpoint - Get list of diplomas
     */
    public function diplomaAction() {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrParams = $this->getUriSegments();
        $len = count($arrParams);

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $diplomaModel = new Diploma();
                $diplomaId = $arrParams[$len-1];
                if (isset($diplomaId)) {
                    $responseData = json_encode($diplomaModel->getDiploma($diplomaId));
                }
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
}