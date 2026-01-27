
<?php

include_once("debug.php");

// --- class tagNull : does nothing --------------------------------------------

class tagNul
{
	public function startTag ($tag, $attr = '') {}
	public function stopTag  ($tag)             {}
	public function closeAll ()                 {}
	public function scTag    ($tag, $attr = '') {}
	public function regLine  ($line)            {}
	public function bump     ($amnt)            {}
}

// --- class tagOut : immidietly displays output -------------------------------

class tagOut
{
	private int $ind = 0;

	private $eol = "\n";

	private $tags = [];

	private function doInd() {
		for ($i=0; $i < $this->ind; ++$i)
			echo '  ';
	}

	public function bump($amnt) { $this->ind += $amnt; }

	public function startTag($tag, $attr = '') {
		$this->doInd();
		echo '<' . $tag;
		if ($attr)
			echo ' ' . $attr . ' ';
		echo '>' . $this->eol;
		$this->ind += 2;
		$this->tags[] = $tag;
	}
	public function stopTag($tag) {
		$this->ind -= 2;
		if ($this->ind < 0) $this->ind = 0;
		$this->doInd();
		echo '</' . $tag;
		echo '>' . $this->eol;
		$tt = array_pop($this->tags);
		if ($tag != $tt) {
			debug_log("tag mismatch " . $tag . " vs " . $tt);
			$arr = debug_backtrace();
			if (array_key_exists(0, $arr))
			{
				$aa = $arr[0];
				$str = "at : ";
				if (array_key_exists('file', $aa))
					$str .= basename($aa['file']) . " ";
				if (array_key_exists('line', $aa))
					$str .= $aa['line'] . " ";
				debug_log($str);
			}
			//debug_log(var_export($arr, true));
		}
		//assert($tag == $tt);
	}
	public function closeAll() {
		while (!empty($this->tags)) {
			$tt = end($this->tags);
			$this->stopTag($tt);
		}
	}
	public function scTag($tag, $attr = '') {
		$this->doInd();
		echo '<' . $tag;
		if ($attr)
			echo ' ' . $attr;
		echo ' />' . $this->eol;
	}
	public function regLine($line) {
		$this->doInd();
		echo $line . $this->eol;
	}
}

// --- class tagDefer : displays output later ----------------------------------

class tagDefer
{
	private int $ind = 0;

	private $eol = "\n";
	
	private $lines = [];

	private $tags = [];

	private function doInd() {
		$s = "";
		for ($i=0; $i < $this->ind; ++$i)
			$s .= '  ';
		return $s;
	}

	public function bump($amnt) { $this->ind += $amnt; }

	public function startTag($tag, $attr = '') {
		$s = $this->doInd();
		$s .= '<' . $tag;
		if ($attr)
			$s .= ' ' . $attr . ' ';
		$s .= '>' . $this->eol;
		$this->ind += 2;
		$this->tags[] = $tag;
		$lines[] = $s;
	}

	public function stopTag($tag) {
		$this->ind -= 2;
		if ($this->ind < 0) $this->ind = 0;
		$s = $this->doInd();
		$s .= '</' . $tag;
		$s .= '>' . $this->eol;
		$lines[] = $s;
		$tt = array_pop($this->tags);
		//assert($tag == $tt);
	}

	public function closeAll() {
		while (!empty($this->tags)) {
			$tt = end($this->tags);
			$this->stopTag($tt);
		}
	}


	public function scTag($tag, $attr = '') {
		$s = $this->doInd();
		$s .= '<' . $tag;
		if ($attr)
			$s .= ' ' . $attr;
		$s .= ' />' . $this->eol;
		$lines[] = $s;
	}
	public function regLine($line) {
		$s = $this->doInd();
		$s .= $line . $this->eol;
		$lines[] = $s;
	}
	
	public function Output()
	{
		foreach ($lines as $l)
			echo $l;
		return $this;
	}
	public function Clear()
	{
		$lines = [];
		$ind = 0;
	}
	public function Callback($callback)
	{
		foreach ($lines as $l)
			$callback($l);
		return $this;
	}

}

// --- class tagAdv : whole page handler ----------------------------------

/*

class tagAdv
{
	private int $ind = 2;

	private $eol = "\n";

	private $title = "";
	private $meta  = [];
	private $lines = [];
	private $style = [];
	private $code  = [];

	private function doI($ii) {
		$s = "";
		for ($i=0; $i < $ii; ++$i)
			$s .= '  ';
		return $s;
	}

	private function doInd() { return $this->doI($this->ind); }

	public function bump($amnt) { $this->ind += $amnt; }

	public function startTag($tag, $attr = '') {
		$s = $this->doInd();
		$s .= '<' . $tag;
		if ($attr)
			$s .= ' ' . $attr . ' ';
		$s .= '>' . $this->eol;
		$this->ind += 2;
		$lines[] = $s;
	}

	public function stopTag($tag) {
		$this->ind -= 2;
		$s = $this->doInd();
		$s .= '</' . $tag;
		$s .= '>' . $this->eol;
		$lines[] = $s;
	}
	public function scTag($tag, $attr = '') {
		$s = $this->doInd();
		$s .= '<' . $tag;
		if ($attr)
			$s .= ' ' . $attr;
		$s .= ' />' . $this->eol;
		$lines[] = $s;
	}
	public function regLine($line) {
		$s = $this->doInd();
		$s .= $line . $this->eol;
		$lines[] = $s;
	}
	
	public function Output()
	{
		function prnt($l) { echo $l . $this->eol; }
		Callback(prnt);
		return $this;
	}
	public function Clear()
	{
		$title = "";
		$meta  = [];
		$lines = [];
		$style = [];
		$code  = [];

		$ind = 2;
	}
	public function Callback($callback)
	{
		foreach ($lines as $l)
			$callback($l);
		return $this;
	}

*/



?>

