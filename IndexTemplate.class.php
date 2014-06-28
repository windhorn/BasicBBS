<?php
class IndexTemplate
{

	function show($tpl_file_path)
	{
		extract((array) $this);
		include($tpl_file_path);
	}
}
 ?>
