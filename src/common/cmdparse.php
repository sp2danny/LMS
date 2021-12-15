
<?php

class Cmd
{
	public $is_empty = true;
	public $is_command = false;
	public $is_text = false;
	public $is_segment = false;
	public $is_comment = false;	
	public $text = '';
	public $rest = '';
	public $segment = '';
	public $command = '';
	public $params = [];
}

function cmdparse($str)
{
	$cmd = new Cmd;
	
	$str = trim($str);
	
	$n = strlen($str);
	
	if ($n == 0) return $cmd;
	
	if ($str[0] == '#') {
		$cmd->is_comment = true;
		return $cmd; // coments also coded as empty
	}

	$cmd->is_empty = false;

	if (($str[0] == '[') && ($str[$n-1] == ']')) {
		$cmd->is_segment = true;
		$cmd->segment = substr($str, 1, $n-2);
		return $cmd;
	}

	if ($str[0] != '!') {
		$cmd->is_text = true;
		$cmd->text = $str;
		return $cmd;
	}

	$cmd->is_command = true;
	$str = substr($str, 1);
	$p = strpos($str, ' ');
	if (!$p) {
		$cmd->command = $str;
	} else {
		$cmd->command = substr($str, 0, $p);
		$cmd->rest = substr($str, $p+1);
		$cmd->params = str_getcsv($cmd->rest, ';');
	}
	return $cmd;
}

?>
