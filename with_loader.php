<?php

/**
 * Class loader function
 *
 * @access	public
 * @param	string
 * @return	object
 */
function load_class($class = '')
{
	$file	= $class.'.php';
	
	if (! file_exists($file) )
		die('Unable to load the specified class: '. $file);
	
	require_once($file);
	
	if (! class_exists($class) )
		die('The specified class: '. $class .' does not exist');
	
	return	new $class();
}


$class	= 'TemplateParser';

$data['title']	= 'Using Template Parser Class';
$data['loop']	= array(
					array(
						'first'		=> 'This is the first variable in first array',
						'second'	=> 'This is the second variable in first array'),
					array(
						'first'		=> 'This is the first variable in second array',
						'second'	=> 'This is the second variable in second array'),
					array(
						'first'		=> 'This is the first variable in third array',
						'second'	=> 'This is the second variable in third array'),
				);

$template	= load_class($class);
$template->display('view/delimiter_1', $data);
?>
