<?php

// Call the TemplateParser.php file
require_once('TemplateParser.php');

if (! class_exists('TemplateParser') )
  die('The specified class: TemplateParser does not exist');


$data['title']	= 'Using Template Parser Class';
$data['head']	= 'Using Delimiter { and }';
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

// Instance the TemplateParser Class
$template	= new TemplateParser();

// Fetch variable data into the destination view
$template->display('view/delimiter_1', $data);

/**
	If you want to use other delimiters for example % symbol overwrite
	the variable value, you can set it up like the code below:

		$data['head']	= 'Using Delimiter % and %';
		$template->set_delimiter('%', '%');
		$template->display('view/delimiter_2', $data);

	You can also use other extension template view file for example text file (.txt):
	
		$data['head']	= 'Using Other File Extension';
		$template->set_extension('txt');
		$template->display('view/text_extension', $data);

*/
?>