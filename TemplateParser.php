<?php

/**
 * Template Parser Class
 * 
 * @category	Parser
 * @author	Ahaddin Gani Arzak
 * @email	admin@agacrycom
 * @link	http://www.agacry.com
 * @reference	CodeIgniter, Smarty
 */
class TemplateParser
{
	private	$_left	= '{';
	private	$_right	= '}';
	private	$_ext	= 'php';
	
	/**
	 * Constructor
	 *
	 * Sets the $config data from the primary config.php file as a class variable
	 *
	 * @access	public
	 * @param	string	the left delimiter
	 * @param	string	the right delimiter
	 * @param	string	view file extension
	 * @return	void
	 */
	public function __construct($_left = '', $_right = '', $_ext = '')
	{
		if (!empty($_left))
			$this->_left	= $_left;
		
		if (!empty($_right))
			$this->_right	= $_right;
		
		if (!empty($_ext))
			$this->_ext	= $_ext;
	}
	
	/**
	 * Set the left/right variable delimiters { and/or }
	 *
	 * @access	public
	 * @param	string	the left delimiter
	 * @param	string	the right delimiter
	 * @return	void
	 */
	public function set_delimiter($_left = '', $_right = '')
	{
		if (!empty($_left))
			$this->_left	= $_left;
		
		if (!empty($_right))
			$this->_right	= $_right;
	}
	
	/**
	 * Set the template view file extension
	 *
	 * @access	public
	 * @param	string	view file extension
	 * @return	void
	 */
	public function set_extension($_ext = '')
	{
		if (!empty($_ext))
			$this->_ext	= $_ext;
	}
	
	/**
	 * Parses pseudo-variables contained in the specified template,
	 * replacing them with the data in the second param
	 * 
	 * @access	private
	 * @param	pointer	passed by reference of string file content
	 * @param	array	data variable
	 * @return	void
	 */
	private function parse(&$_line, $data)
	{
		foreach ($data as $key => $val) :
			if (!is_array($val))
				$_line	= $this->parse_string($key, $val, $_line);
			else
				$_line	= $this->parse_array($key, $val, $_line);
		endforeach;
	}
	
	/**
	 * Parse a single key and value
	 *
	 * @access	private
	 * @param	string
	 * @param	string
	 * @param	string
	 * @return	string
	 */
	private function parse_string($_key, $_val, $_string)
	{
		return str_replace(trim($this->_left.$_key.$this->_right), $_val, $_string);
	}
	
	/**
	 * Parses an array format to tag pairs {loop} ... {/loop}
	 *
	 * @access	private
	 * @param	string
	 * @param	string
	 * @param	string
	 * @return	string
	 */
	private function parse_array($_key, $_val, $_string)
	{
		if (! ($match = $this->replace_array($_key, $_string)))
			return $_string;
		
		$str = '';
		
		foreach ($_val as $row)
		{
			$line = $match[1];	// variable in array or pair tag
			
			foreach ($row as $key => $val)
			{
				if ( ! is_array($val))
					$line = $this->parse_string($key, $val, $line);
				else
					$line = $this->parse_array($key, $val, $line);
				
			}

			$str .= $line;
		}
		
		return str_replace(trim($match[0]), $str, $_string);
	}
	
	/**
	 * Detect and replace the tag pairs {loop} ... {/loop}
	 * 
	 * @access	private
	 * @param	string
	 * @param	string
	 * @return	array or boolean (FALSE)
	 */
	private function replace_array($_key, $_string)
	{
		// Using RegEx with string format: "|\{VARIABLE\}(.+?)\{/VARIABLE\}|s"
		// Will return FALSE if no match pair variable
		if ( ! preg_match("|" . preg_quote($this->_left) . $_key . preg_quote($this->_right) . "(.+?)". preg_quote($this->_left) . '/' . $_key . preg_quote($this->_right) . "|s", $_string, $match))
			return FALSE;

		return $match;
	}
	
	/**
	 * Display a parsed variables to the requested template view
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @return	void
	 */
	public function display($template = '', $data = array())
	{
		$_line	= '';
		$_path	= $template.'.'.$this->_ext;
		
		// Checking for the existing template view file
		if ( ! file_exists($_path) )
			die('<pre>Unable to load the requested file: '.$_path.'</pre>');
		
		// Start open file
		if ( $file = fopen($_path,'r') ) :
			
			// Fetch for each lines
			while ( ! feof($file) )
				$_line .= fgets($file);
			
			// Parses variable tag with the value of tag
			$this->parse($_line, $data);
			
			// Display parsed variable tags
			echo $_line;
			
			// If you open any file, don't forget to close it
			fclose($file);
		endif;
		
	}
	
	/**
	 * Clean up variables inside the class script
	 *
	 * @access	public
	 * @param	void
	 * @return	void
	 */
	public function __destruct()
	{
		if ( isset($this) ) :
			
			foreach ($this as $key => $val)
				unset($this->$key);
			
		endif;
	}
}
?>
