<?php
/**
 * This file is part of the InaneClasses package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Philip Michael Raab <peep@inane.co.za>
 * @package Inane\Debug
 *
 * @license MIT
 * @license http://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <philip@inane.co.za>
 */
namespace Inane\Debug;

/**
 * Log to html with pre & code tags
 *
 * @package Inane\Debug
 * @namespace \Inane\Debug
 * @version 0.6.0
 */
class Logger {
	/**
	 * @var Logger The reference to *Singleton* instance of this class
	 * @access private
	 * @static
	 */
	private static $instance;

	/**
	 * @var array The source of the log call
	 * @access protected
	 * @static
	 */
	protected $source;

	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @access public
	 * @static
	 * @return Logger The *Singleton* instance.
	 */
	public static function log() {
		if (null === static::$instance) {
			static::$instance = new Logger();
		}
		
		return static::$instance;
	}

	/**
	 * Protected constructor to prevent creating a new instance of the
	 * *Singleton* via the `new` operator from outside of this class.
	 */
	protected function __construct() {
		$d = debug_backtrace();
		$d2 = $d[2];
		$d3 = $d[3];
		$this->source = [
			'file' => $d2['file'],
			'line' => $d2['line'],
			'class' => $d3['class'],
			'function' => $d3['function'],
		];
	}

	/**
	 * Private clone method to prevent cloning of the instance of the
	 * *Singleton* instance.
	 *
	 * @return void
	 */
	private function __clone() {
	}

	/**
	 * Private wakeup method to prevent unserialize called on the *Singleton* 
	 * instance.
	 *
	 * @return void
	 */
	private function __wakeup(): void {
	}
	
	/**
	 * @var bool end execution after dump
	 */
	protected $_die = true;
	
	/**
	 * @var string buffer for building output
	 */
	protected $_output = '';

	/**
	 * Builds the dump header
	 * 
	 * @param string $label for dump
	 * 
	 * @return \Inane\Debug\Logger
	 */
	protected function header($label = ''): Logger {
		if ($label != '')
			$label = "<h3 class=\"debug-header\">{$label}</h3>";

		$label .= "<div><strong style='width: 80px;display: inline-block;'>Class: </strong><span>{$this->source['class']}</span></div>";
		$label .= "<div><strong style='width: 80px;display: inline-block;'>Function: </strong><span>{$this->source['function']}</span></div>";
		$label .= "<div><strong style='width: 80px;display: inline-block;'>Line: </strong><span>{$this->source['line']}</span></div>";
		$label .= "<div><strong style='width: 80px;display: inline-block;'>File: </strong><span>{$this->source['file']}</span></div>";
		
		$this->_output = "<div class=\"inane-debug\">{$label}<pre class=\"debug-code\"><code>";
		return $this;
	}

	/**
	 * Log with a label using `print_r`
	 * 
	 * @param mixed $var
	 * @param string $label
	 * @return \Inane\Debug\Logger
	 */
	protected function doLogging($var, string $label = ''): Logger {
		if ($label != '')
			$label .= ': ';
		
		$this->_output = $label . print_r($var, true);
		return $this;
	}

	/**
	 * Log without a label using `print_r`
	 * 
	 * @param mixed $var
	 * @return \Inane\Debug\Logger
	 */
	protected function doPrint($var): Logger {
		$this->_output .= print_r($var, true);
		return $this;
	}

	/**
	 * Log using `var_dump`
	 * 
	 * @param mixed $var
	 * @return \Inane\Debug\Logger
	 */
	protected function doDump($var): Logger {
		echo $this->_output;
		$this->_output = '';
		
		var_dump($var);
		return $this;
	}

	/**
	 * Create footer for dump
	 * 
	 * @param bool|null $die
	 * @return \Inane\Debug\Logger
	 */
	protected function footer($die): Logger {
		if ($die === null)
			$die = $this->_die;
		
		$out = '</code></pre></div>';
		
		if ($this->_output == '')
			echo $out;
		else
			$this->_output .= $out;
		
		if ($die)
			exit();
		
		$this->_die = false;
		return $this;
	}

	/**
	 * Build out
	 * 
	 * @param bool $return
	 * 
	 * @return string|bool
	 */
	protected function out(bool $return = false) {
		$out = $this->_output;
		$this->_output = '';
		
		if ($return === true)
			return $out;
		
		if ($out != '')
			echo $out;
		
		return false;
	}
	
	/**
	 * Output variable using `var_dump`
	 * 
	 * Does a var_dump inside some formatting.
	 * 
	 * @deprecated
	 * @see Logger::dump
	 *
	 * @param mixed $var
	 * @param string $label
	 * @param bool $die
	 * 
	 * @return \Inane\Debug\Logger
	 */
	public static function echo($var, $label = null, $die = null): Logger {
		return static::log()->dumper($var, $label, $die);
	}

	/**
	 * Output variable using `var_dump`
	 * 
	 * Does a var_dump inside some formatting. 
	 *
	 * @param mixed $var
	 * @param string $label
	 * @param bool $die
	 * 
	 * @return \Inane\Debug\Logger
	 */
	public static function dump($var, $label = null, $die = null): Logger {
		return static::log()->dumper($var, $label, $die);
	}	

	/**
	 * Output variable using log
	 *
	 * @param mixed $var        	
	 * @param string $label        	
	 * @param bool $die
	 * 
	 * @return string|bool
	 */
	public function logger($var, $label = null, $die = null) {
		return $this->doLogging($var, $label)->out(true);
	}

	/**
	 * Output variable using print_r
	 *
	 * @param mixed $var        	
	 * @param string $label        	
	 * @param bool $die
	 * @param bool $return
	 *         	
	 * @return string|bool
	 */
	public function printer($var, $label = null, $die = null, bool $return = false) {
		return $this->header($label)->doPrint($var)->footer($die)->out($return);
	}

	/**
	 * Output variable using var_dump
	 *
	 * @param mixed $var        	
	 * @param string $label        	
	 * @param bool $die
	 * 
	 * @return \Inane\Debug\Logger
	 */
	public function dumper($var, $label = null, $die = null): Logger {
		return $this->header($label)->doDump($var)->footer($die);
	}
}
