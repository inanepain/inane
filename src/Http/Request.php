<?php
/**
 * Request
 * 
 * PHP version 8
 */
namespace Inane\Http;

use Inane\Config\Options;
use Inane\Http\Request\AbstractRequest;
use PropertyException;

/**
 * Request
 * 
 * @version 0.5.0
 * 
 * @package Http
 */
class Request extends AbstractRequest {
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
    private $_properties = [];

    protected $_magic_properties_allowed = ['method'];

    /**
     * strings to remove from property names
     */
    static array $_propertyClean = ['request_', 'http_'];

    /**
     * Response
     * 
     * @var Response
     */
    private $response;










    

    /**
     * magic method: __get
     *
     * @param string $property - property name
     *
     * @return mixed the value of $property
     *
     * @throws PropertyException
     */
    public function __get(string $property) {
        if (!$this->_allowAllProperties && !in_array($property, $this->_magic_properties_allowed)) throw new PropertyException($property, 10);

        // TODO: Temp only => to upgrade implementations
        if (str_starts_with($property, 'http')) throw new PropertyException($property, 20);

        return $this->_properties->offsetGet($property, null);
    }

    /**
     * Response 
     * @param bool $allowAllProperties 
     * @return void 
     */
    public function __construct(bool $allowAllProperties = true, ?Response $response = null) {
        $this->_allowAllProperties = ($allowAllProperties === true);
        if (!is_null($response)) $this->response = $response;
        $this->bootstrapSelf();
    }

    /**
     * setup request
     *
     * @return void
     */
    private function bootstrapSelf() {
        $data = [];
        foreach ($_SERVER as $key => $value) $data[$this->toCamelCase($key)] = $value;

        if ($this->_allowAllProperties) $this->_magic_properties_allowed = array_keys($data);

        $this->_properties = new Options($data);
    }

    private function toCamelCase($string) {
        $result = str_replace(static::$_propertyClean, '', strtolower($string));

        preg_match_all('/_[a-z]/', $result, $matches);
        foreach ($matches[0] as $match) {
            $c = str_replace('_', '', strtoupper($match));
            $result = str_replace($match, $c, $result);
        }

        return $result;
    }

    /**
     * get: request => body
     * 
     * @return void|array body
     */
    // public function getBody() {
    //     if ($this->method === static::METHOD_GET) return;

    //     if ($this->method == static::METHOD_POST) {
    //         $body = [];
    //         foreach ($_POST as $key => $value) $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);

    //         return $body;
    //     }
    // }

    public function getAccept() {
        $accept = explode(',', $this->accept);
        $type = 'text/html';
        if (in_array('application/json', $accept) || in_array('*/*', $accept)) $type = 'application/json';
        else if (in_array('application/xml', $accept)) $type = 'application/xml';
        return $type;
    }

    public function getResponse(?string $body = null, $status = 200) {
        if (!isset($this->response)) {
            $this->response = $body == null ? new Response() : new Response($body, $status, ['Content-Type' => $this->getAccept()]);
            $this->response->setRequest($this);
        } else if (!is_null($body)) $this->response->setBody($body);
        return $this->response;
    }

    protected $_post;
    public function getPost() {
        if (!$this->_post) $this->_post = new Options($_POST);
        return $this->_post;
    }

    /**
     * Query Params
     * 
     * @var Options
     */
    private $_query;

    /**
     * get: Query Params
     * 
     * @param null|string $param get specific param
     * @param null|string $default 
     * @return mixed param/params
     */
    public function getQuery(?string $param = null, ?string $default = null): mixed {
        if (!$this->_query) $this->_query = new Options($_GET);

        if (!is_null($param)) return $this->_query->get($param, $default);
        return $this->_query;
    }

    /**
     * get: query string with any modifications
     *
     * @return string query string
     */
    public function buildQuery(): string {
        return http_build_query($this->getQuery()->toArray());
    }

    protected array $_files;
    /**
     * get: uploaded files, if any
     *
     * @return array files
     */
    public function getFiles(): array {
        if (!$this->_files) $this->_files = $_FILES;
        return $this->_files;
    }
}
