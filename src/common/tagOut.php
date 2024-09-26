
<?php

// --- class tagNull : does nothing --------------------------------------------

class tagNul
{
	public function startTag ($tag, $attr = '') {}
	public function stopTag  ($tag)             {}
	public function scTag    ($tag, $attr = '') {}
	public function regLine  ($line)            {}
}

// --- class tagOut : immidietly displays output -------------------------------

class tagOut
{
	private int $ind = 0;

	private $eol = "\n";

	private function doInd() {
		for ($i=0; $i < $this->ind; ++$i)
			echo '  ';
	}

	public function startTag($tag, $attr = '') {
		$this->doInd();
		echo '<' . $tag;
		if ($attr)
			echo ' ' . $attr . ' ';
		echo '>' . $this->eol;
		$this->ind += 2;
	}
	public function stopTag($tag) {
		$this->ind -= 2;
		$this->doInd();
		echo '</' . $tag;
		echo '>' . $this->eol;
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

	private function doInd() {
		$s = "";
		for ($i=0; $i < $this->ind; ++$i)
			$s .= '  ';
		return $s;
	}

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
		function prnt($l) { echo $l . . $this->eol; }
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



?>

