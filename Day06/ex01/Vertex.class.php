<?php

Class Vertex
{
    private $_x;
    private $_y;
    private $_z;
    private $_w;
    private $_color;
    public static $verbose = false;

    public function __construct( array $kwargs )
    {
        $this->_x = $kwargs['x'];
        $this->_y = $kwargs['y'];
        $this->_z = $kwargs['z'];
        $this->_color = (isset($kwargs['color']) ? $kwargs['color'] : new Color( ['rgb' => 0xffffff] ));
        $this->_w = (isset($kwargs['w']) ? $kwargs['w'] : 1.0);
        if (self::$verbose == true)
            echo "$this constructed" . PHP_EOL;
    }

    public function __destruct()
    {
        if (self::$verbose == true)
            echo "$this destructed" . PHP_EOL;
    }

    public function __toString()
    {
        return (sprintf("Vertex( x: %.2f, y: %.2f, z:%.2f, w:%.2f%s )", $this->_x, $this->_y, $this->_z, $this->_w, (self::$verbose == true ? ", $this->_color" : "")));
    }

    public static function doc()
    {
        echo file_get_contents("./Vertex.doc.txt");
    }

    public function getX() { return ($this->_x); }
    public function getY() { return ($this->_y); }
    public function getZ() { return ($this->_z); }
    public function getW() { return ($this->_w); }
    public function getColor() { return ($this->_color); }

    public function setX($x) { $this->_x = $x; return; }
    public function setY($y) { $this->_y = $y; return; }
    public function setZ($z) { $this->_z = $z; return; }
    public function setW($w) { $this->_w = $w; return; }
    public function setColor($color) { $this->_color = $color; return; }

}

?>