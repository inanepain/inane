<?php

/**
 * Response
 * 
 * PHP version 7
 */

declare(strict_types=1);

namespace Inane\Http;

use Inane\Config\Options;
use Inane\Debug\Writer;
use Inane\Exception\UnexpectedValueException;
use Inane\Exception\BadMethodCallException;
use Inane\File\FileInfo;
use Inane\Http\Request\IRequest;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
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
class Response extends Message implements ResponseInterface {
    public static int $rm = 4;

    /**
     * response body
     */
    protected string $body;

    /**
     * Http Status
     */
    protected StatusCode $status;

    /**
     * request
     */
    protected Request $request;

    /**
     * sleep: bandwidth delay
     */
    protected int $_sleep = 0;

    /**
     * Size of download
     */
    protected int $_downloadSize = 0;

    /**
     * Start serving file from
     */
    protected int $_downloadStart = 0;

    /**
     * File to serve
     */
    private FileInfo $_file;

    public function withStatus($code, $reasonPhrase = '') { }

    public function getReasonPhrase() { }





    /**
     * set: request
     * 
     * @param RequestInterface $request request
     * @return Response response
     */
    public function setRequest(RequestInterface $request): self {
        if (!isset($this->request)) $this->request = $request;
        return $this;
    }

    /**
     * get: request
     * 
     * @return Request request
     */
    public function getRequest(): Request {
        if (!isset($this->request)) $this->request = new Request(true, $this);
        return $this->request;
    }

    /**
     * Response
     * 
     * @param string|resource|StreamInterface|null $body    Request body
     * @param int|StatusCode $status 
     * @param array $headers headers
     * 
     * @return void 
     * 
     * @throws UnexpectedValueException 
     * @throws BadMethodCallException 
     */
    public function __construct($body = null, int|StatusCode $status = 200, array $headers = []) {
        if (!is_null($body)) {
            if (!($body instanceof StreamInterface)) $body = new Stream($body);
            $this->stream = $body;
        }
        $this->setHeaders($headers);
        $this->setStatus($status);
    }

    /**
     * Create response from array
     *
     * @param array $array
     * @return Response
     */
    public static function fromArray(array $array): Response {
        $opt = new Options($array);
        $response = new static($opt->get('body', ''), $opt->get('status', 200), $opt->get('headers', []));
        if ($opt->offsetExists('request')) $response->setRequest($opt->get('request'));
        return $response;
    }

    /**
     * array to xml
     *
     * @param array $data
     * @param SimpleXMLElement $xml_data
     * @return void
     */
    protected function arrayToXml($data, SimpleXMLElement &$xml_data) {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key)) $key = 'item' . $key;
                $subnode = $xml_data->addChild($key);
                $this->arrayToXml($value, $subnode);
            } else {
                $xml_data->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }

    /**
     * add header
     * 
     * @param string $name 
     * @param mixed $value 
     * @param bool $replace 
     * @return Response 
     */
    public function addHeader(string $name, mixed $value, bool $replace = true): self {
        $normalized = strtolower($name);
        
        if (isset($this->headerNames[$normalized])) {
            $name = $this->headerNames[$normalized];
            $this->headers[$name] = array_merge($this->headers[$name], $value);
        } else {
            $this->headerNames[$normalized] = $name;
            $this->headers[$name] = $value;
        }

        $this->headers[$name] = [$value];
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
     * get: status
     * 
     * @return StatusCode status
     */
    public function getStatus(): StatusCode {
        return $this->status;
    }

    /**
     * set: status code
     * 
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

    /**
     * set body
     *
     * @param string $body
     * @return self
     */
    public function setBody(string $body): self {
        $this->stream = new Stream($body);
        return $this;
    }

    /**
     * get body
     * 
     * @return string body
     */
    public function getContents(): string {
        $body = $this->getBody()->getContents();
        if (in_array($this->getHeaderLine('Content-Type'), ['application/json', '*/*']))
            return json_encode($body);
        else if (in_array($this->getHeaderLine('Content-Type'), ['application/xml'])) {
            $xml = new SimpleXMLElement('<root/>');
            $this->arrayToXml($body, $xml);
            return $xml->asXML();
        }

        return $body;
    }

    /**
     * download response
     *
     * @return bool
     */
    public function isDownload(): bool {
        return isset($this->_file);
    }

    /**
     * force download
     *
     * @return bool
     */
    public function isForceDownload(): bool {
        return $this->getHeaderLine('Content-Description') == 'File Transfer' ? true : false;
    }

    /**
     * throttled download
     *
     * @return bool
     */
    public function isThrottled(): bool {
        return $this->_sleep > 0 ? true : false;
    }

    /**
     * file info
     *
     * @return FileInfo
     */
    public function getFileInfo(): FileInfo {
        return $this->_file;
    }

    /**
     * download start position
     *
     * @return int
     */
    public function getDownloadFrom(): int {
        return $this->_downloadStart;
    }

    /**
     * sleep between buffers
     *
     * @return int
     */
    public function getSleep(): int {
        return $this->_sleep;
    }

    /**
     * Sets download limit 0 = unlimited
     *
     * This is a rough kb/s speed. But very rough
     *
     * @param  $kbSec
     * @return Response
     */
    protected function setBandwidth(int $kbSec = 0): self {
        if (static::$rm > 0) $kbSec = $kbSec / static::$rm;
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
        if (static::$rm > 0) $sleep = $sleep * static::$rm;
        return $sleep;
    }

    /**
     * Set file to download
     * 
     * $speed 0 = no limit
     * 
     * @param mixed $src_file file
     * @param bool $force download, not view in browser
     * @param int $speed kbSec
     * 
     * @return Response
     * 
     * @throws UnexpectedValueException 
     * @throws BadMethodCallException 
     */
    public function setFile($src_file, bool $force = false, int $speed = 0): self {
        $file = new FileInfo($src_file);
        $this->_file = $file;

        if (!$file->isValid()) {
            $this->setStatus(StatusCode::NOT_FOUND());
            $this->setBody('file invalid:' . $this->_file->getPathname());
            return $this;
        }

        $this->setStatus(StatusCode::OK());
        $fileSize = $this->_file->getSize();
        $this->_downloadSize = $fileSize;
        $this->_downloadStart = 0;

        if ($this->getRequest()->range != null) $this->updateRange();
        $this->updateFileHeaders();
        if ($force) $this->forceDownload();
        $this->setBandwidth($speed);

        return $this;
    }

    /**
     * update headers for file downloads
     *
     * @return void
     */
    protected function updateFileHeaders() {
        $this->addHeader('Accept-Ranges', 'bytes');
        $this->addHeader('Content-type', $this->_file->getMimetype() ?? 'application/octet-stream');
        $this->addHeader("Pragma", "no-cache");
        $this->addHeader('Cache-Control', 'public, must-revalidate, max-age=0');
        $this->addHeader("Content-Length", $this->_downloadSize);
    }

    /**
     * update range headers for downloads
     *
     * @return void
     */
    protected function updateRange() {
        $req = explode('=', $this->getRequest()->range);
        $ranges = explode(',', $req[1]);
        $ranges = explode('-', $ranges[0]);

        $fileSize = $this->_file->getSize();

        $start = (int) $ranges[0];
        $stop = (int) ($ranges[1] == '' ? $fileSize - 1 : $ranges[1]);

        $this->_downloadSize = $stop - $start + 1;
        $this->_downloadStart = $start;
        $downloadRange = "bytes {$start}-{$stop}/{$fileSize}";

        $this->setStatus(StatusCode::PARTIAL_CONTENT());
        $this->addHeader('Content-Range', $downloadRange);
    }

    /**
     * update headers for forced downloads
     *
     * @return void
     */
    protected function forceDownload() {
        $this->addHeader("Content-Description", 'File Transfer');
        $this->addHeader('Content-Disposition', 'attachment; filename="' . $this->_file->getFilename() . '";');
        $this->addHeader("Content-Transfer-Encoding", "binary");
    }
}
