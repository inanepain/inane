<?php
/**
 * Http
 *
 * Http Client
 * 
 * PHP version 8
 *
 * @author Philip Michael Raab <peep@inane.co.za>
 * @package Http
 * @copyright 2021 Inane
 */

declare(strict_types=1);

namespace Inane\Http;

use SplObserver;
use SplSubject;

use function fclose;
use function feof;
use function flush;
use function fopen;
use function fread;
use function fseek;
use function header;
use function http_response_code;
use function is_array;
use function round;
use function set_time_limit;
use function usleep;

/**
 * Client
 * 
 * Sends Http messages
 * 
 * @see /Users/philip/Temp/mime/mt.php
 * for mimetype updating
 * 
 * @package Http
 * @version 1.6.0
 */
class Client implements SplSubject {
    /**
     * SplObserver[] observers
     */
    private array $observers = [];

    /**
     * File size served
     *
     * @var int
     */
    protected $_progress = 0;

    /**
     * File size served %
     *
     * @var int
     */
    protected $_percent = 0;

    /**
     * Client
     */
    public function __construct() {
    }

    /**
     * Attach Observer
     * 
     * @param SplObserver $observer_in observer
     * @return Client
     */
    public function attach(SplObserver $observer_in): self {
        $this->observers[] = $observer_in;

        return $this;
    }

    /**
     * Detach Observer
     * 
     * @param SplObserver $observer_in observer
     * @return Client 
     */
    public function detach(SplObserver $observer_in): self {
        foreach ($this->observers as $key => $oval)
            if ($oval == $observer_in)
                unset($this->observers[$key]);

        return $this;
    }

    /**
     * Notify observers
     *
     * @return Client
     */
    public function notify(): self {
        foreach ($this->observers as $obs)
            $obs->update($this);

        return $this;
    }

    /**
     * Progress of download
     *
     * @return array
     */
    public function getProgress(): array {
        return [
            'filename' => $this->_file->getFilename(),
            'progress' => $this->_progress,
            'total' => $this->_file->getSize()
        ];
    }

    /**
     * add progress
     * 
     * @param int $progress
     * @return Client
     */
    protected function addProgress($progress, $fileSize): self {
        $this->_progress += $progress;
        if ($this->_progress > $fileSize)
            $this->_progress = $fileSize;

        $percent = round($this->_progress / $fileSize * 100, 0);
        if ($percent != $this->_percent) {
            $this->notify();
            $this->_percent = $percent;
        }
        return $this;
    }

    /**
     * send headers
     *
     * @param Response $response
     * @return void
     */
    protected function sendHeaders(Response $response): void {
        if ($response->getStatus()->equals(StatusCode::PARTIAL_CONTENT()))
            header($response->getStatus()->getDefault());
        else if ($response->getStatus() == StatusCode::OK())
            header($response->getStatus()->getDefault());

        http_response_code($response->getStatus()->getValue());

        foreach ($response->getHeaders() as $header => $value) {
            if (is_array($value)) foreach ($value as $val) header("$header: $val");
            else if ($value == '') header($header);
            else header("$header: $value");
        }
    }

    /**
     * serve response
     *
     * @param Response $response response
     * @param int $options flags
     * @return void
     */
    public function send(Response $response): void {
        if ($response->isDownload()) $this->serveFile($response);
        else $this->sendResponse($response);
        exit(0);
    }

    /**
     * send response
     *
     * @param Response $response
     * @return void
     */
    protected function sendResponse(Response $response): void {
        $this->sendHeaders($response);
        echo $response->getBody();
    }

    /**
     * serve file
     *
     * @param Response $response
     * @return void
     */
    protected function serveFile(Response $response): void {
        $file = $response->getFileInfo();
        $byte_from = $response->getDownloadFrom();
        $byte_to = (int)$response->getHeaderLine('Content-Length');
        $fp = fopen($file->getPathname(), 'r');
        fseek($fp, $byte_from);
        $this->_progress = $byte_from;

        if ($response->isThrottled()) $this->sendBuffer($response, $fp);
        else $this->sendResponse($response->setBody(fread($fp, $byte_to)));

        fclose($fp);
    }

    /**
     * send chunk to client
     * 
     * @param Response $response 
     * @param resource $fp 
     * @return void 
     */
    protected function sendBuffer(Response $response, $fp) {
        if (ob_get_level() == 0) ob_start();
        $this->sendHeaders($response);
        $sleep = $response->getSleep();
        $buffer_size = 1024 * 8; // 8kb
        $download_size = (int)$response->getHeaderLine('Content-Length');

        while (!feof($fp)) {
            set_time_limit(0);
            print(fread($fp, $buffer_size));
            ob_flush();
            flush();
            $this->addProgress($buffer_size, $download_size);
            usleep($sleep);
        }
        ob_end_flush();
        $this->addProgress(1, $download_size);
    }
}
