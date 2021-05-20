<?php

/**
 * Response
 * 
 * PHP version 7
 */

namespace Inane\Http;

use Inane\Config\Options;
use Inane\Debug\Writer;
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
    public static int $rm = 4;

    /**
     * response headers
     */
    protected array $headers = [];

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
     * sleep: bandwith delay
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
        if (!isset($this->request)) $this->request = new Request(true, $this);
        return $this->request;
    }

    /**
     * Response
     * 
     * @param string $body 
     * @param int|StatusCode $status 
     * @param array $headers headers
     * 
     * @return void 
     * 
     * @throws UnexpectedValueException 
     * @throws BadMethodCallException 
     */
    public function __construct(string $body = '', int|StatusCode $status = 200, array $headers = []) {
        $this->body = $body;
        $this->headers = $headers;

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

    /**
     * add header
     * 
     * @param string $name 
     * @param mixed $value 
     * @param bool $replace 
     * @return Response 
     */
    public function addHeader(string $name, mixed $value, bool $replace = true): self {
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
     * get headers
     * 
     * @return array headers
     */
    public function getHeaders(): array {
        return $this->headers;
    }

    /**
     * get header
     *
     * @param string $name
     * @param null|int|string $default
     * @return null|int|string|array
     */
    public function getHeader(string $name, null|int|string|array $default = null): null|int|string|array {
        if (array_key_exists($name, $this->getHeaders())) return $this->getHeaders()[$name];
        return $default;
    }

    /**
     * set body
     *
     * @param string $body
     * @return self
     */
    public function setBody(string $body): self {
        $this->body = $body;
        return $this;
    }

    /**
     * get body
     * 
     * @return string body
     */
    public function getBody(): string {
        $body = $this->body;
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
        return $this->getHeader('Content-Description') == 'File Transfer' ? true : false;
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
        $this->_downloadSize = $fileSize;
        $this->_downloadStart = 0; // no range, download from 0

        if ($this->getRequest()->range != null) $this->updateRange();
        $this->updateFileHeders();
        if ($force) $this->forceDownload();
        $this->setBandwidth($speed);

        return $this;
    }

    /**
     * update headers for file downloads
     *
     * @return void
     */
    protected function updateFileHeders() {
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

        $this->_downloadSize = $stop - $start + 1; // the download length
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
