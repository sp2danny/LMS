
<?php

class tagOut
{

    private int $ind = 0;

    private $eol = "\n";

    private function doInd() {
        for ($i=0; $i < $this->ind; ++$i)
            echo ' ';
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

?>

