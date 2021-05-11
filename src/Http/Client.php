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

use Inane\File\FileInfo;
use Inane\Option\BitwiseFlagTrait;
use SplObserver;
use SplSubject;

use function explode;
use function fclose;
use function feof;
use function flush;
use function fopen;
use function fread;
use function fseek;
use function header;
use function http_response_code;
use function is_array;
use function is_null;
use function round;
use function set_time_limit;
use function usleep;

/**
 * Client
 * 
 * Sends Http messages
 * 
 * @package Http
 * @version 1.5.0
 */
class Client implements SplSubject {
    use BitwiseFlagTrait;

    /**
     * Force file to download
     */
    const FORCE_DOWNLOAD = 1 << 0;

    /**
     * Slower download
     */
    const THROTTLE_SPEED = 1 << 1;

    // const FLAG_REGISTERED = 1 << 2;

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
     * sleep: bandwith delay
     *
     * @var int
     */
    protected $sleep = 0;

    /**
     * Client
     * 
     * @param  $kbSec = 0
     */
    public function __construct(?int $kbSec = null) {
        if (!is_null($kbSec)) $this->setBandwidth($kbSec);
    }

    /**
     * Sets download limit 0 = unlimited
     *
     * This is a rough kb/s speed. But very rough
     *
     * @param  $kbSec
     * @return Client
     */
    public function setBandwidth(int $kbSec = 0): self {
        $this->sleep = $kbSec * 4.3;
        if ($this->sleep > 0)
            $this->sleep = (8 / $this->sleep) * 1e6;

        return $this;
    }

    /**
     * Attach Observer
     * 
     * @param SplObserver $observer_in observer
     * @return Client
     */
    public function attach(SplObserver $observer_in): self {
        //could also use array_push($this->observers, $observer_in);
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
        foreach ($this->observers as $okey => $oval)
            if ($oval == $observer_in)
                unset($this->observers[$okey]);

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

    protected function sendHeaders(Response $response): void {
        if ($response->getStatusCode() == Response::PARTIAL_CONTENT)
            header("HTTP/1.1 206 Patial Content");
        else if ($response->getStatusCode() == Response::OK)
            header("HTTP/1.1 200 OK");
        else http_response_code($response->getStatusCode());

        foreach ($response->getHeaders() as $header => $value) {
            if (is_array($value)) foreach ($value as $val) header("$header: $val");
            else if ($value == '') header($header);
            else header("$header: $value");
        }
    }

    /**
     * send response
     *
     * @param Response $response
     * @return void
     */
    public function send(Response $response): void {
        if (!$this->isFlagSet(Client::THROTTLE_SPEED)) {
            $this->sendHeaders($response);
            echo $response->getBody();
        }
        exit(0);
    }

    /**
     * send file
     *
     * @param string $srcfile
     * @param int $options flags
     * @return void
     */
    public function sendFile(string $srcfile, int $options = 0): void {
        $this->flags = $options;

        $file = new FileInfo($srcfile);
        $file->setInfoClass('\Inane\File\FileInfo');

        $request = new Request();
        $response = $request->getResponse();

        if (!$file->isValid()) {
            $response->setStatusCode(Response::NOT_FOUND);
            $response->setBody('file invalid:' . $this->_file->getPathname());
            $this->send($response);
        }

        $fileSize = $file->getSize();

        if ($request->range != null) {
            $response->setStatusCode(Response::PARTIAL_CONTENT);
            $this->_progress = 0;
            $this->_percent = 0;
            [
                'unit' => $unit,
                'range' => $range
            ] = explode('=', $request->range);

            $ranges = explode(',', $range);
            $ranges = explode('-', $ranges[0]);

            $byte_from = (int) $ranges[0];
            $byte_to = (int) ($ranges[1] == '' ? $fileSize - 1 : $ranges[1]);
            $download_size = $byte_to - $byte_from + 1; // the download length

            $download_range = 'bytes ' . $byte_from . "-" . $byte_to . "/" . $fileSize; // the download range
            $response->addHeader('Content-Range', $download_range);
        } else {
            $response->setStatusCode(Response::OK);
            $byte_from = 0; // no range, download from 0
            $download_size = $fileSize;
            $byte_to = $fileSize - 1;
        }

        // set headers
        $mimetype = $file->getMimetype();
        if ($file->getExtension() == 'css') $mimetype = 'text/css';
        else if ($file->getExtension() == 'js') $mimetype = 'application/javascript';

        $response->addHeader('Accept-Ranges', 'bytes');
        $response->addHeader('Content-type', $mimetype);
        $response->addHeader("Pragma", "no-cache");
        $response->addHeader('Cache-Control', 'public, must-revalidate, max-age=0');
        $response->addHeader("Content-Length", $download_size);

        if ($this->isFlagSet(Client::FORCE_DOWNLOAD)) {
            $response->addHeader("Content-Description", 'File Transfer');
            $response->addHeader('Content-Disposition', 'attachment; filename="' . $file->getFilename() . '";');
            $response->addHeader("Content-Transfer-Encoding", "binary");
        }

        $fp = fopen($file->getPathname(), "r"); // open file
        fseek($fp, $byte_from); // seek to start byte
        $this->_progress = $byte_from;

        if ($this->isFlagSet(Client::THROTTLE_SPEED)) {
            if (ob_get_level() == 0) ob_start();
            $this->sendHeaders($response);
            $buffer_size = 1024 * 8; // 8kb

            while (!feof($fp)) { // start buffered download
                set_time_limit(0); // reset time limit for big files
                print(fread($fp, $buffer_size));
                ob_flush();
                flush();
                $this->addProgress($buffer_size, $download_size);
                usleep($this->sleep); // sleep for speed limitation
            }
            ob_end_flush();
            $this->addProgress(1, $download_size);
        } else {
            $countent = fread($fp, $download_size);
            $response->setBody($countent);
        }
        
        fclose($fp); // close file
        $this->send($response);
    }
}
