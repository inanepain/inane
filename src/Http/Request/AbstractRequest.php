<?php

namespace Inane\Http\Request;

use Inane\Http\Exception\PropertyException;
use Inane\Http\Response;
use Inane\Config\Options;

use function array_keys;
use function strtolower;
use function preg_match_all;
use function str_replace;
use function strtoupper;
use function filter_input;
use function is_null;
use function in_array;

/**
 * AbstractRequest
 * 
 * @version 0.5.0
 */
abstract class AbstractRequest implements IRequest {
    public const METHOD_COPY = 'COPY';
    public const METHOD_DELETE = 'DELETE';
    public const METHOD_GET = 'GET';
    public const METHOD_LINK = 'LINK';
    public const METHOD_LOCK = 'LOCK';
    public const METHOD_OPTIONS = 'OPTIONS';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_POST = 'POST';
    public const METHOD_PROPFIND = 'PROPFIND';
    public const METHOD_PURGE = 'PURGE';
    public const METHOD_PUT = 'PUT';
    public const METHOD_UNLINK = 'UNLINK';
    public const METHOD_UNLOCK = 'UNLOCK';
    public const METHOD_VIEW = 'VIEW';

    protected $_allowAllProperties = true;

    /**
     * properties
     * 
     * @var Options
     */
    protected $_properties = [];

    protected $_magic_properties_allowed = ['method'];

    /**
     * Response
     * 
     * @var Response
     */
    protected $response;

    /**
     * magic method: __get
     *
     * @param string $property - propert name
     *
     * @return mixed the value of $property
     *
     * @throws PropertyException
     */
    public function __get(string $property) {
        if (!$this->_allowAllProperties && !in_array($property, $this->_magic_properties_allowed)) throw new PropertyException($property, 10);
        else if (!$this->_properties->offsetExists($property)) throw new PropertyException($property, 20);

        return $this->_properties->offsetGet($property, null);
    }

    function __construct(bool $allowAllProperties = true) {
        $this->_allowAllProperties = ($allowAllProperties === true);
        $this->bootstrapSelf();
    }

    private function bootstrapSelf() {
        $data = [];
        foreach ($_SERVER as $key => $value) $data[$this->toCamelCase($key)] = $value;

        if ($this->_allowAllProperties) $this->_magic_properties_allowed = array_keys($data);

        $this->_properties = new Options($data);
    }

    private function toCamelCase($string) {
        $result = str_replace('request_', '', strtolower($string));

        preg_match_all('/_[a-z]/', $result, $matches);

        foreach ($matches[0] as $match) {
            $c = str_replace('_', '', strtoupper($match));
            $result = str_replace($match, $c, $result);
        }

        return $result;
    }

    public function getBody() {
        if ($this->method === static::METHOD_GET) return;

        if ($this->method == static::METHOD_POST) {
            $body = [];
            foreach ($_POST as $key => $value) $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);

            return $body;
        }
    }

    public function getAccept() {
        $accept = explode(',', $this->httpAccept);
        $type = 'text/html';
        if (in_array('application/json', $accept) || in_array('*/*', $accept)) $type = 'application/json';
        else if (in_array('application/xml', $accept)) $type = 'application/xml';
        return $type;
    }

    public function getResponse($body = '', $status = 200) {
        if (!$this->response) $this->response = new Response($body, $status, ['Content-Type' => $this->getAccept()]);
        return $this->response;
    }

    protected $_post;
    public function getPost() {
        if (!$this->_post) $this->_post = new Options($_POST);
        return $this->_post;
    }

    protected $_query;
    public function getQuery(?string $param = null) {
        if (!$this->_query) $this->_query = new Options($_GET);

        if (!is_null($param)) return $this->_query[$param];
        return $this->_query;
    }

    protected $_files;
    public function getFiles() {
        // if (!$this->_files) $this->_files = new Config($_FILES);
        if (!$this->_files) $this->_files = $_FILES;
        return $this->_files;
    }
}
