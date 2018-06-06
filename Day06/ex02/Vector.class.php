<?php

class Vector
{
    private $_x;
    private $_y;
    private $_z;
    private $_w;
    public static $verbose = false;

    public function __construct( array $kwargs )
    {
        if (isset($kwargs['orig'])) {
            $this->_x = $kwargs['dest']->getX() - $kwargs['orig']->getX();
            $this->_y = $kwargs['dest']->getY() - $kwargs['orig']->getY();
            $this->_z = $kwargs['dest']->getZ() - $kwargs['orig']->getZ();
        }
        else {
            $tmp = new Vertex( ['x' => 0, 'y' => 0, 'z' => 0] );
            $this->_x = $kwargs['dest']->getX() - $tmp->getX();
            $this->_y = $kwargs['dest']->getY() - $tmp->getY();
            $this->_z = $kwargs['dest']->getZ() - $tmp->getZ();
        }
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
        return (sprintf("Vector( x:%.2f, y:%.2f, z:%.2f, w:%.2f )", $this->_x, $this->_y, $this->_z, $this->_w));
    }

    public function doc()
    {
        echo file_get_contents("./Vector.doc.txt");
    }

    public function getX() { return ($this->_x); }
    public function getY() { return ($this->_y); }
    public function getZ() { return ($this->_z); }
    public function getW() { return ($this->_w); }

    /* ------------------------------------------------------------------------- */

    public function magnitude()
    {
        return (sqrt(pow($this->getX(), 2) + pow($this->getY(), 2) + pow($this->getZ(), 2)));
    }

    public function normalize()
    {
        if ($this->magnitude() == 1)
            return (clone $this);
        else
            return (new Vector( ['dest' => new Vertex( ['x' => $this->getX() / $this->magnitude(), 'y' => $this->getY() / $this->magnitude(), 'z' => $this->getZ() / $this->magnitude()] )] ));
    }

    public function add( Vector $rhs )
    {
        return (new Vector( ['dest' => new Vertex( ['x' => $this->getX() + $rhs->getX(), 'y' => $this->getY() + $rhs->getY(), 'z' => $this->getZ() + $rhs->getZ()] )] ));
    }

    public function sub( Vector $rhs )
    {
        return (new Vector( ['dest' => new Vertex( ['x' => $this->getX() - $rhs->getX(), 'y' => $this->getY() - $rhs->getY(), 'z' => $this->getZ() - $rhs->getZ()] )] ));
    }

    public function opposite()
    {
        return (new Vector( ['dest' => new Vertex( ['x' => -$this->getX(), 'y' => -$this->getY(), 'z' => -$this->getZ()] )] ));
    }

    public function scalarProduct( $k )
    {
        return (new Vector( ['dest' => new Vertex( ['x' => $this->getX() * $k, 'y' => $this->getY() * $k, 'z' => $this->getZ() * $k] )] ));
    }

    public function dotProduct( Vector $rhs )
    {
        return ($this->getX() * $rhs->getX() + $this->getY() * $rhs->getY() + $this->getZ() * $rhs->getZ());
    }

    public function cos( Vector $rhs )
    {
        return $this->dotProduct($rhs) / ($this->magnitude() * $rhs->magnitude());
    }

    public function crossProduct( Vector $rhs )
    {
        return (new Vector( ['dest' => new Vertex( ['x' => $this->getY() * $rhs->getZ() - $this->getZ() * $rhs->getY(), 'y' => $this->getZ() * $rhs->getX() - $this->getX() * $rhs->getZ(), 'z' => $this->getX() * $rhs->getY() - $this->getY() * $rhs->getX() ] )] ));
    }

}

?>