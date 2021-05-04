<?php
/**
 * Http
 *
 * Http Client
 * 
 * PHP version 7
 *
 * @author Philip Michael Raab <philipr@digitalcabinet.co.za>
 * @package Http
 *
 * @copyright 2021 Digitalcabinet
 */
namespace Inane\Http;

use function header;
use function is_array;
use function http_response_code;

/**
 * Client
 * 
 * Sends Http messages
 * 
 * @package Http
 * @version 1.0.0
 */
class Client {
    public function send(Response $response) {
        http_response_code($response->getStatusCode());
        foreach ($response->getHeaders() as $header => $value) {
            if (is_array($value)) foreach ($value as $val) header("$header: $val", false);
            else if ($value == '') header($header, false);
            else header("$header: $value", false);
        }
        echo $response->getBody();
        exit;
    }
}
