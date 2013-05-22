<?php
    /**
	 * cli.class.php
     * cli is meant to be a simple include for the commandline.
     * It provides _autoloading functionality for other classes and options handling.
     * You can extend the class or even pass it as a simple cli object.
     *
     * @package cli.class.php
     * @author Duane Jeffers <duane@jeffe.rs>
     * @version 0.1
     * @copyright Copyright (c) 2012-2013, Duane Jeffers <duane@jeffe.rs>
     *
     * Copyright (c) 2012-2013 Duane Jeffers <duane@jeffe.rs>
     *
     * Permission is hereby granted, free of charge, to any person obtaining a copy
     * of this software and associated documentation files (the "Software"), to deal
     * in the Software without restriction, including without limitation the rights
     * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
     * copies of the Software, and to permit persons to whom the Software is
     * furnished to do so, subject to the following conditions:
     *
     * The above copyright notice and this permission notice shall be included in
     * all copies or substantial portions of the Software.
     *
     * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
     * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
     * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
     * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
     * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
     * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
     * THE SOFTWARE.
     **/

	// cliException is only for displaying the errors on the commandline.
	class cliException
	extends Exception {
		
	}

    class cli {
        /* Constants */
        /* Output Color Constants */
        const ColorFgBlack    = '0;30';
        const ColorFgRed      = '0;31';
        const ColorFgGreen    = '0;32';
        const ColorFgBrown    = '0;33';
        const ColorFgBlue     = '0;34';
        const ColorFgPurple   = '0;35';
        const ColorFgCyan     = '0;36';
        const ColorFgLtGray   = '0;37';
        const ColorFgDkGray   = '1;30';
        const ColorFgLtRed    = '1;31';
        const ColorFgLtGreen  = '1;32';
        const ColorFgYellow   = '1;33';
        const ColorFgLtBlue   = '1;34';
        const ColorFgLtPurple = '1;35';
        const ColorFgLtCyan   = '1;36';
        const ColorFgWhite    = '1;37';

        const ColorBgBlack   = '40';
        const ColorBgRed     = '41';
        const ColorBgGreen   = '42';
        const ColorBgYellow  = '43';
        const ColorBgBlue    = '44';
        const ColorBgMagenta = '45';
        const ColorBgCyan    = '46';
        const ColorBgLtGray  = '47';

        /* Public Static Functions */

        /* color_str() is a static function that returns a formatted string to echo to STDOUT
         *
         * @param string $string The string to be colorized.
         * @param string $fore The foreground color. Must be a const ColorFg*
         * @param string $back The background color. Must be a const ColorBg*
         * @return string The color encapsulated string.
         */
        public static function color_str($string, $fore = self::ColorFgRed, $back = NULL) {
            $ret_string = '';
            if(is_string($fore)) {
                $ret_string .= "\033[" . $fore . "m";
            }

            if(is_string($back)) {
                $ret_string .= "\033[" . $back . "m";
            }

            $ret_string .= $string . "\033[0m";
            return $ret_string;
        }
		
		/* usr_home_dir() is a static function that helps get the user directory on *nix and Windows Systems.
		 * @return string The running user's home directory.
		 */
		public static function usr_home_dir() {
			// Find windows home directory first.
			$home_dir = getenv('HOMEDRIVE').getenv('HOMEPATH').'//';
			if($home_dir == '//') {
				$home_dir = getenv('HOME') . '/';
			}
			
			return $home_dir;
		}

        /* Private Variables */

        /* Protected Variables */
        protected $_options      = array();
        protected $_autoloadOpts = array('pre' => NULL, 'post' => '.class', 'ext' => '.php');
        protected $_verbose      = FALSE; // Defaults to false. This will add a 'verbose' and 'v' option to the extended options. - IF v or verbose is one of the list of options, then the code will not over ride that.
        protected $_help         = array();
		protected $_optionFile   = array('file_loc' => NULL, 'parse_type' => 'INI');
        protected $_script;

        /* Public Variables */

        /* Private Functions */

        /* Protected Functions */

        /* _autoload is the main autoloading class.
         *
         * @param string $class The class name to pass to the include.
         */
        protected function _autoload($class) {
            $inc_file = $this->_autoloadOpts['pre'] . $class . $this->_autoloadOpts['post'] . $this->_autoloadOpts['ext'];
            include $inc_file;
        }

        /* Public Functions */

        /* opt() will check to see if the option was called.
         *
         * @param string $option This is the option being called.
         * @param bool $return If true, this will return the value of the option
         * @return mixed Will return TRUE or FALSE if the return is FALSE, else it will return the value if it exists.
         */
        public function opt($option, $return = FALSE) {
            if(array_key_exists($option, $this->_options) && $return) {
                return $this->_options[$option];
            } elseif(array_key_exists($option, $this->_options)) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
		
		/* print_line(string $msg) will print a newline-returned string to the console.
		 *
		 * @param string $msg The line to print to the console.
		 * @return void Prints the message with a newline character to the console.
		 */
        public function print_line($msg) {
            echo $msg . "\n";
        }
		
		/* print_dump($msg) will print a newline-returned var_dump to the console.
		 *
		 * @param mixed $msg The item to be var_dump-ed.
		 * @return void Prints the var_dump and a newline character to the console.
		 */
        public function print_dump($msg) {
            echo var_export($msg, true) . "\n";
        }
		
		/* print_exception(cliException $e, $die) will print the exception error, formatted for the commandline.
		 *
		 * @param cliException $e The cliException object
		 * @param bool $die Will kill the script after printing the exception.
		 * @return void Prints the exception to the command line.
		 */
		public function print_exception(cliException $e, $die = TRUE) {
			$this->print_line('cliException Thrown');
			$this->print_line($e->getCode() . ' - ' . $e->getMessage());
			$this->print_line('File: ' . $e->getFile() . '[' . $e->getLine() .']');
			$this->print_line('');
			
			foreach($e->getTrace() as $tid => $trace_line) {
				$this->print_line($tid . ' ' . $trace_line);
			}
			
			if($die) {
				die();
			}
			
			return $this;
		}

        /* print_help prints out a nicely formatted help message with the description in the options array.
         *
         * @param string $opt The option that the application is expecting.
         * @param string $msg Any message to send as a prepend before displaying the options.
         * @param bool $quit Will exit out of the cli program.
         */
        public function print_help($opt, $msg = NULL, $quit = FALSE) {
            if(!$this->opt($opt)) {
                return;
            }

			// Calculate the size of the message.
			if(!is_null($msg)) {
				$msg_len = strlen($msg);
			}


            $dash = function($key) {
                if(strlen($key) > 1)
                    return '--' . $key;
                else return '-' . $key;
            };

            $switch = array();
            foreach($this->_help as $key => $val) {
                if(substr($key, -2) == '::') {
                    // Not a required value
                    $key = trim($key, '::');
                    $key = $dash($key) . '(=<optional_value>)';
                } elseif(substr($key, -1) == ':') {
                    // A required value
                    $key = trim($key, ':');
                    $key = $dash($key) . '=<required_value>';
                } else {
                    $key = $dash($key);
                }
                $switch[$key] = $val;
            }

            $white_space_key = max(array_map('strlen', array_keys($switch)));
			$white_space_value = max(array_map('strlen', array_values($switch)));
			$white_space_total = ($white_space_key + $white_space_value) + 3;

			if(!is_null($msg)) {
				// Only change the spacing if the message is longer.
				if($msg_len > $white_space_total) {
					$white_space_total = $msg_len;
					$white_space_value = $white_space_total - ($white_space_key + 3);
				}

				// Print the message header.
				$this->print_line('+-' . str_pad('', $white_space_total, '-') . '-+');
				$this->print_line('| ' . str_pad($msg, $white_space_total, ' ', STR_PAD_BOTH) . ' |');
			}

			// Print the box header.
			$this->print_line('+-' . str_pad('', $white_space_total, '-') . '-+');

            foreach($switch as $key => $value) {
				$this->print_line('| ' . str_pad($key, $white_space_key) . ' | ' . str_pad($value, $white_space_value) . ' |');
			}

			$this->print_line('+-' . str_pad('', $white_space_total, '-') . '-+');

            if($quit) {
                $this->print_line(NULL);
                die();
            }
        }

        /* include_path allows for setting include paths for the application.
         *
         * @param array $paths an array with the path locations to set the include paths. */
        public function include_path(array $paths) {
            $inc_paths = implode(PATH_SEPARATOR, $paths);

            set_include_path(get_include_path() . PATH_SEPARATOR . $inc_paths);

            return $this;
        }

        /* autoload sets up the cli object as an autoloader.
         *
         * @param string $post The postfix for the class file name. DEFAULT: '.class'
         * @param string $pre The prefix for the class file name. DEFAULT: NULL
         * @param string $ext The include extension. Useful for adding: '.inc.php' DEFAULT: '.php'
         */
        public function autoload($post = '.class', $pre = NULL, $ext = '.php') {
            $this->autoload = array('post' => $post, 'pre' => $pre, 'ext' => $ext);
            spl_autoload_register(array($this, '_autoload'));

            return $this;
        }

        /* verbose() is an alias of print_line, with the added function to check if the -v or --verbose switch is sent.
         *
         * @param string $msg The message to send to STDOUT
         */
        public function verbose($msg) {
            if($this->_verbose) {
                $this->print_line($msg);
            }
        }
		
		/* setOptFile() is where the system options can use defaults before loading the major code for the system.
		 * *NOTE* Since this is for the options, only single-dimension arrays are allowed.
		 *
		 * @param string $file_loc The option file location
		 * @param string $parse_type The parse type of the option file. (Currently Supporting ini and JSON).
		 * @param bool   $autoload This will autoload the options into the options array. (Default is TRUE)
		 */
		public function setOptFile($file_loc, $parse_type, $autoload = TRUE) {
			$this->_optionFile['file_loc'] = $file_loc;
			$this->_optionFile['parse_type'] = $parse_type;
			
			if($autoload === TRUE) {
				$this->loadOpts();
			}
			return $this;
		}
		
		/* loadOpts() is the way to load in options from an options ini or json file. */
		public function loadOpts() {
			if(!is_file($this->_optionFile['file_loc'])) {
				// This file does not exist. Skip.
				return FALSE;
			}
			
			switch(strtolower($this->_optionFile['parse_type'])) {
				case 'ini':
					$opt_arr = parse_ini_file($this->_optionFile['file_loc']);
					break;
				
				case 'json':
					$file_contents = file_get_contents($this->_optionFile['file_loc']);
					$opt_arr = json_decode($file_contents, TRUE);
					unset($file_contents);
					break;
			}
			
			foreach($opt_arr as $option => $value) {
				if(!$this->opt($option)) { // Only add in the values that were not specified in the array.
					$this->_options[$option] = $value;
				}
			}
			unset($opt_arr);
			
			return $this;
		}
		
		/* saveOpts($opts, $update) will save the options that are set in the class to the option file that was set with setOptFile();
		 *
		 * @param mixed $opts A (Comma Separated) list or array of the options to save.
		 * @param bool $update This will force an update on the file, if the file exists.
		 */
		public function saveOpts($opts, $update = FALSE) {
			if((is_file($this->_optionFile['file_loc']) && $update == TRUE)
			   || (!is_file($this->_optionFile['file_loc']))) {
				if(is_string($opts)) {
					$options = explode(',', $opts);
				} elseif(is_array($opts)) {
					$options = $opts;
				}
				
				$out = array();
				switch(strtolower($this->_optionFile['parse_type'])) {
					case 'ini':
						foreach($options AS $optval) {
							if($this->opt($optval)) {
								$out[] = $optval . '=' . $this->opt($optval, TRUE);
							}
						}
						$output = implode("\r\n", $out);
						break;
					
					case 'json':
						foreach($options as $optval) {
							if($this->opt($optval)) {
								$out[$optval] = $this->opt($optval, TRUE);
							}
						}
						
						$output = json_encode($out);
						break;
				}
					return file_put_contents($this->_optionFile['file_loc'], $output);
			}
			
			return FALSE;
		}

        /* __construct sets up the cli object for running.
         * The options array is setup as such: array('<option>' => '<description>'); - <description> can be NULL
         *
         * @param array $options sets up a list of switches that the cli application is expecting.
         * @param bool $v Defaults to TRUE, this enables the -v switch.
         * @param bool $verbose Defaults to FALSE, this enables the --verbose switch.
         * @return object The instanciated class.
         */
        public function __construct(array $options, $v = TRUE, $verbose = FALSE) {
            $long_opts = $opts = array();
            $this->_help = $options;
            // Cycle through the array and add the options.
            foreach(array_keys($options) as $opt) {
                if((strlen($opt) > 3)) { // short opts can have up to three characters. If your longopt is 3 characters, rethink it ...
                    // This is an extended option. - add to the longOpt arr.
                    $long_opts[] = $opt;
                } else {
                    // This is a simple option.
                    $opts[] = $opt;
                }
            }

            // Check if overriding the verbosity.
            if($v) {
                $opts[] = 'v';
                $this->_help['v'] = 'Allow Application to Print Output to the Screen.';
            }

            if($verbose) {
                $long_opts[] = 'verbose';
                $this->_help['verbose'] = 'Allow Application to Print Output to the Screen.';
            }

			if(!empty($opts) || !empty($long_opts)) { // This just saves the call in case there is nothing to call.
				$this->_options = getopt(implode($opts), $long_opts); // Set the options.

				if(($v && $this->opt('v'))) {
				    $this->_verbose = TRUE;
				} elseif(($verbose && $this->opt('verbose'))) {
				    $this->_verbose = TRUE;
				}
			}

            return $this;
        }

        public function setVerbose($verbose = TRUE) {
            $this->_verbose = $verbose;
            return $this;
        }
    }