<?php

namespace Inane\Http;

use Inane\Config\Options;
use SimpleXMLElement;

use function array_key_exists;
use function in_array;
use function json_encode;
use function is_string;
use function is_numeric;
use function htmlspecialchars;

class Response {
    protected $headers = [];
    protected $body;
    protected $statusCode = 200;

    public function __construct($body = '', $statusCode = 200, $headers = []) {
        $this->body = $body;
        $this->headers = $headers;
        $this->statusCode = $statusCode;
    }

    public static function fromArray(array $array) {
        $config = New Options($array);
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

    public function setStatusCode($statusCode) {
        $this->statusCode = $statusCode;
    }

    public function getStatusCode() {
        return $this->statusCode;
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