<?php

Class Camera
{
    private $_origin;
    private $_orientation;
    private $_width;
    private $_height;
    private $_ratio;
    private $_fov;
    private $_near;
    private $_far;
    private $_tT;
    private $_tR;
    private $_proj;
    public static $verbose = false;

    private function tTCtor()
    {
        $this->_tT = new Matrix( ['preset' => Matrix::IDENTITY] );
        $this->_tT->setMatrix( [
            'a' => ['x' => 1.0, 'y' => 0.0, 'z' => 0.0, 'w' => -$this->getOrigin()->getX()],
            'b' => ['x' => 0.0, 'y' => 1.0, 'z' => 0.0, 'w' => -$this->getOrigin()->getY()],
            'c' => ['x' => 0.0, 'y' => 0.0, 'z' => 1.0, 'w' => -$this->getOrigin()->getZ()],
            'd' => ['x' => 0.0, 'y' => 0.0, 'z' => 0.0, 'w' => $this->getOrigin()->getW()],
        ] );
        return;
    }

    private function tRCtor()
    {
        $this->_tR = new Matrix( ['preset' => Matrix::IDENTITY] );
        $this->_tR->setMatrix( [
            'a' => ['x' => $this->getOrientation()->getMatrix()['c']['z'], 'y' => $this->getOrientation()->getMatrix()['a']['y'], 'z' => $this->getOrientation()->getMatrix()['c']['x'], 'w' => $this->getOrientation()->getMatrix()['a']['w']],
            'b' => ['x' => $this->getOrientation()->getMatrix()['b']['x'], 'y' => $this->getOrientation()->getMatrix()['b']['y'], 'z' => $this->getOrientation()->getMatrix()['b']['z'], 'w' => $this->getOrientation()->getMatrix()['b']['w']],
            'c' => ['x' => $this->getOrientation()->getMatrix()['a']['z'], 'y' => $this->getOrientation()->getMatrix()['c']['y'], 'z' => $this->getOrientation()->getMatrix()['a']['x'], 'w' => $this->getOrientation()->getMatrix()['c']['w']],
            'd' => ['x' => $this->getOrientation()->getMatrix()['d']['x'], 'y' => $this->getOrientation()->getMatrix()['d']['y'], 'z' => $this->getOrientation()->getMatrix()['d']['z'], 'w' => $this->getOrientation()->getMatrix()['d']['w']],
        ] );
        return;
    }

    private function projCtor()
    {
        $this->_proj = new Matrix( ['preset' => Matrix::PROJECTION, 'fov' => $this->_fov, 'ratio' => $this->_ratio, 'near' => $this->_near, 'far' => $this->_far] );
        return;
    }

    private function viewMatrix()
    {
        return $this->_tR->mult($this->_tT);
    }

    private function projMatrix()
    {
        return $this->_proj;
    }

    public function __construct( array $kwargs )
    {
        $this->_origin = $kwargs['origin']; // This is a Vertex
        $this->_orientation = $kwargs['orientation']; // This is a Matrix
        $this->_width = $kwargs['width'];
        $this->_height = $kwargs['height'];
        $this->_ratio = $this->_width / $this->_height;
        $this->_fov = $kwargs['fov'];
        $this->_near = $kwargs['near'];
        $this->_far = $kwargs['far'];
        $this->tTCtor();
        $this->tRCtor();
        $this->projCtor();
        if (self::$verbose == true)
            echo "Camera instance constructed" . PHP_EOL;
    }

    public function __destruct()
    {
        if (self::$verbose == true)
            echo "Camera instance destructed" . PHP_EOL;
    }

    public function doc()
    {
        echo file_get_contents("Camera.doc.txt");
    }

    public function __toString()
    {
        return (sprintf("Camera(\n+ Origine: %s\n+ tT:\n%s\n+ tR:\n%s\n+ tR->mult( tT ):\n%s\n+ Proj:\n%s\n)", $this->getOrigin(), $this->_tT, $this->_tR, $this->viewMatrix(), $this->_proj));
    }

    public function getOrigin() { return $this->_origin; }
    public function getOrientation() { return $this->_orientation; }

    public function watchVertex( Vertex $worldVertex )
    {
        $camVertex = $this->viewMatrix()->transformVertex($worldVertex);
        $NDCVertex = $this->projMatrix()->transformVertex($camVertex);
        return (new Vertex( ['x' => ($NDCVertex->getX() + 1) * 0.5 * $this->_width, 'y' => ($NDCVertex->getY() + 1) * 0.5 * $this->_height, 'z' => 0] ));
    }

}

?>