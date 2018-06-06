<?php

class Color
{
    public $red;
    public $green;
    public $blue;
    public static $verbose = false;

    public function __construct( array $kwargs )
    {
        if (array_key_exists('rgb', $kwargs)) {
            $this->red = $kwargs['rgb'] >> 16;
            $this->green = $kwargs['rgb'] >> 8 & 255;
            $this->blue = $kwargs['rgb'] & 255;
        }
        else {
            $this->red = intval($kwargs['red']);
            $this->green = intval($kwargs['green']);
            $this->blue = intval($kwargs['blue']);
        }
        if (self::$verbose == true)
            echo "$this constructed." . PHP_EOL;
    }

    public function __destruct()
    {
        if (self::$verbose == true)
            echo "$this destructed." . PHP_EOL;
    }

    public function __toString()
    {
        return (sprintf("Color( red: %3d, green: %3d, blue: %3d )", $this->red, $this->green, $this->blue));
    }

    public static function doc()
    {
        echo file_get_contents("./Color.doc.txt");
    }

    public function add( Color $rhs )
    {
        return (new Color( ['red' => $this->red + $rhs->red, 'green' => $this->green + $rhs->green, 'blue' => $this->blue + $rhs->blue] ));
    }

    public function sub( Color $rhs )
    {
        return (new Color( ['red' => $this->red - $rhs->red, 'green' => $this->green - $rhs->green, 'blue' => $this->blue - $rhs->blue] ));
    }

    public function mult( $f )
    {
        return (new Color( ['red' => $this->red * $f, 'green' => $this->green * $f, 'blue' => $this->blue] ));
    }
}

?>