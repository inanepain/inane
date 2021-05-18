<?php

/**
 * Response
 * 
 * PHP version 7
 */

namespace Inane\Http;

use Inane\Config\Options;
use Inane\Exception\UnexpectedValueException;
use Inane\Exception\BadMethodCallException;
use SimpleXMLElement;

use function array_key_exists;
use function htmlspecialchars;
use function in_array;
use function is_numeric;
use function is_string;
use function json_encode;

/**
 * Response
 * 
 * @version 0.5.0
 * 
 * @package Http
 */
class Response {
    protected $headers = [];
    protected $body;
    /**
     * Http Status
     * 
     * @var StatusCode
     */
    protected StatusCode $status;

    public function __construct(string $body = '', int|StatusCode $status = 200, array $headers = []) {
        $this->body = $body;
        $this->headers = $headers;

        $this->setStatus($status);
    }

    public static function fromArray(array $array) {
        $config = new Options($array);
        return new self($config->get('body', ''), $config->get('status', 200), $config->get('headers', []));
    }

    protected function arrayToXml($data, &$xml_data) {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key)) {
                    $key = 'item' . $key; //dealing with <0/>..<n/> issues
                }
                $subnode = $xml_data->addChild($key);
                $this->arrayToXml($value, $subnode);
            } else {
                $xml_data->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }

    public function addHeader($name, $value, bool $replace = true): self {
        if ($replace == false && array_key_exists($name, $this->getHeaders())) return $this;
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * get: status
     * 
     * @param StatusCode|int $status 
     * @return Response 
     * @throws UnexpectedValueException 
     * @throws BadMethodCallException 
     */
    public function setStatus(StatusCode|int $status): self {
        if ($status instanceof StatusCode) $this->status = $status;
        else $this->status = StatusCode::from($status);
        return $this;
    }

    /**
     * get: statud
     * 
     * @return StatusCode status
     */
    public function getStatus(): StatusCode {
        return $this->status;
    }

    /**
     * set: status code
     * @param mixed $statusCode 
     * @return self 
     * @throws UnexpectedValueException 
     * @throws BadMethodCallException 
     * @deprecated 0.5.0
     */
    public function setStatusCode($statusCode):self {
        return $this->setStatus($statusCode);
    }

    /**
     * get: status code
     *
     * @return int
     */
    public function getStatusCode(): int {
        return $this->getStatus()->getValue();
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function getHeader($name, $default = null) {
        if (array_key_exists($name, $this->getHeaders())) return $this->getHeaders()[$name];
        return $default;
    }

    public function setBody($body): self {
        $this->body = $body;
        return $this;
    }

    public function getBody() {
        $body = $this->body;

        if (is_string($body)) return $body;
        if (in_array($this->getHeader('Content-Type'), ['application/json', '*/*'])) {
            return json_encode($body);
        } else if (in_array($this->getHeader('Content-Type'), ['application/xml'])) {
            $xml = new SimpleXMLElement('<root/>');
            $this->arrayToXml($body, $xml);
            return $xml->asXML();
        }

        return json_encode($body);
    }
}
