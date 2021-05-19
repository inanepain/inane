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
use Inane\File\FileInfo;
use Inane\Http\Request\IRequest;
use SimpleXMLElement;

use function array_key_exists;
use function htmlspecialchars;
use function in_array;
use function is_numeric;
use function is_null;
use function json_encode;

/**
 * Response
 * 
 * @version 0.6.0
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

    /**
     * request
     * 
     * @var Request
     */
    protected Request $request;

    /**
     * set: request
     * 
     * @param Request $request request
     * @return Response response
     */
    public function setRequest(IRequest $request): self {
        if (!isset($this->request)) $this->request = $request;
        return $this;
    }

    /**
     * get: request
     * 
     * @return Request request
     */
    public function getRequest(): Request {
        if (!isset($this->request)) $this->request = new Request();
        return $this->request;
    }

    public function __construct(string $body = '', int|StatusCode $status = 200, array $headers = []) {
        $this->body = $body;
        $this->headers = $headers;

        $this->setStatus($status);
    }

    public static function fromArray(array $array) {
        $config = new Options($array);
        $response = new self($config->get('body', ''), $config->get('status', 200), $config->get('headers', []));
        if ($config->offsetExists('request')) $response->setRequest($config->get('request'));
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
    public function setStatusCode($statusCode): self {
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

        // if (is_string($body)) return $body;
        if (in_array($this->getHeader('Content-Type'), ['application/json', '*/*'])) {
            return json_encode($body);
        } else if (in_array($this->getHeader('Content-Type'), ['application/xml'])) {
            $xml = new SimpleXMLElement('<root/>');
            $this->arrayToXml($body, $xml);
            return $xml->asXML();
        }

        return $body;
        // return json_encode($body);
    }

    public function isDownload():bool {
        return isset($this->_file);
    }

    public function isForceDownload():bool {
        return $this->getHeader('Content-Description') == 'File Transfer' ? true : false;
    }

    public function isThrottled():bool {
        return $this->_sleep > 0 ? true : false;
    }

    private FileInfo $_file;

    public function getFileInfo(): FileInfo {
        return $this->_file;
    }

    public function getDownloadFrom(): int {
        return $this->_byte_from;
    }

    public function getSleep(): int {
        return $this->_sleep;
    }

    /**
     * Set file to download
     * 
     * $speed 0 = no limit
     * 
     * @param mixed $srcfile file
     * @param bool $force download, not view in browser
     * @param int $speed kbSec
     * 
     * @return Response
     * 
     * @throws UnexpectedValueException 
     * @throws BadMethodCallException 
     */
    public function setFile($srcfile, bool $force = false, int $speed = 0): self {
        $file = new FileInfo($srcfile);
        $this->_file = $file;

        if (!$file->isValid()) {
            $this->setStatus(StatusCode::NOT_FOUND());
            $this->setBody('file invalid:' . $this->_file->getPathname());
            return $this;
        }

        $this->setStatus(StatusCode::OK());
        $fileSize = $this->_file->getSize();
        $this->_download_size = $fileSize;
        $this->_byte_from = 0; // no range, download from 0
        $this->_byte_to = $fileSize - 1;

        if ($this->getRequest()->range != null) $this->updateRange();
        $this->updateFileHeders();
        if ($force) $this->forceDownload();
        $this->setBandwidth($speed);

        return $this;
    }

    /**
     * sleep: bandwith delay
     *
     * @var int
     */
    protected $_sleep = 0;
    /**
     * File size to download
     *
     * @var int
     */
    protected $_download_size = 0;
    /**
     * Download start
     *
     * @var int
     */
    protected $_byte_from = 0;

    /**
     * Sets download limit 0 = unlimited
     *
     * This is a rough kb/s speed. But very rough
     *
     * @param  $kbSec
     * @return Response
     */
    protected function setBandwidth(int $kbSec = 0): self {
        $this->_sleep = $kbSec * 4.3;
        if ($this->_sleep > 0)
            $this->_sleep = (8 / $this->_sleep) * 1e6;

        return $this;
    }

    /**
     * gets download limit 0 = unlimited
     *
     * This is a rough kb/s speed. But very rough
     *
     * @return int kbSec
     */
    public function getBandwidth(?int $sleep = null): int {
        if (is_null($sleep)) $sleep = $this->getSleep();
        if ($sleep > 0)
            $sleep = (8 / ($sleep / 1e6)) / 4.3;
        return $sleep;
    }

    protected function updateFileHeders() {
        $this->addHeader('Accept-Ranges', 'bytes');
        $this->addHeader('Content-type', $this->_file->getMimetype());
        $this->addHeader("Pragma", "no-cache");
        $this->addHeader('Cache-Control', 'public, must-revalidate, max-age=0');
        $this->addHeader("Content-Length", $this->_download_size);
    }

    protected function updateRange() {
        $this->setStatus(StatusCode::PARTIAL_CONTENT());

        [
            'unit' => $unit,
            'range' => $range
        ] = explode('=', $this->getRequest()->range);

        $ranges = explode(',', $range);
        $ranges = explode('-', $ranges[0]);

        $fileSize = $this->_file->getSize();

        $byte_from = (int) $ranges[0];
        $byte_to = (int) ($ranges[1] == '' ? $fileSize - 1 : $ranges[1]);

        $this->_download_size = $byte_to - $byte_from + 1; // the download length
        $this->_byte_from = $byte_from;

        $download_range = 'bytes ' . $byte_from . "-" . $byte_to . "/" . $fileSize; // the download range
        $this->addHeader('Content-Range', $download_range);
    }

    protected function forceDownload() {
        $this->addHeader("Content-Description", 'File Transfer');
        $this->addHeader('Content-Disposition', 'attachment; filename="' . $this->_file->getFilename() . '";');
        $this->addHeader("Content-Transfer-Encoding", "binary");
    }
}
