<?php

class Matrix
{
    Const IDENTITY = 0;
    Const SCALE = 1;
    Const RX = 2;
    Const RY = 3;
    Const RZ = 4;
    Const TRANSLATION = 5;
    Const PROJECTION = 6;

    private $_preset;
    private $_scale;
    private $_angle;
    private $_vtc;
    private $_fov;
    private $_ratio;
    private $_near;
    private $_far;
    private $_matrix;
    public static $verbose = false;

    private static function getConstName($k)
    {
        $reflect = new ReflectionClass('Matrix');
        $constants = $reflect->getConstants();
        foreach ($constants as $name => $value) {
            if ($k == Matrix::RX)
                return "Ox ROTATION";
            else if ($k == Matrix::RY)
                return "Oy ROTATION";
            else if ($k == Matrix::RZ)
                return "Oz ROTATION";
            else if ($value == $k)
                return $name;
        }
    }

    private static function getRow($l, $row)
    {
        return (sprintf("\n%s | %.2f | %.2f | %.2f | %.2f", $l, $row['x'], $row['y'], $row['z'], $row['w']));
    }

    private function buildIdentityMatrix()
    {
        $this->_matrix = [
            'a' => ['x' => 1.0, 'y' => 0.0, 'z' => 0.0, 'w' => 0.0],
            'b' => ['x' => 0.0, 'y' => 1.0, 'z' => 0.0, 'w' => 0.0],
            'c' => ['x' => 0.0, 'y' => 0.0, 'z' => 1.0, 'w' => 0.0],
            'd' => ['x' => 0.0, 'y' => 0.0, 'z' => 0.0, 'w' => 1.0]
        ];
    }

    private function buildScaleMatrix()
    {
        $this->_matrix = [
            'a' => ['x' => $this->getScale(), 'y' => 0.0, 'z' => 0.0, 'w' => 0.0],
            'b' => ['x' => 0.0, 'y' => $this->getScale(), 'z' => 0.0, 'w' => 0.0],
            'c' => ['x' => 0.0, 'y' => 0.0, 'z' => $this->getScale(), 'w' => 0.0],
            'd' => ['x' => 0.0, 'y' => 0.0, 'z' => 0.0, 'w' => 1.0]
        ];
    }

    private function buildRotXMatrix()
    {
        $this->_matrix = [
            'a' => ['x' => 1.0, 'y' => 0.0, 'z' => 0.0, 'w' => 0.0],
            'b' => ['x' => 0.0, 'y' => cos($this->getAngle()), 'z' => -sin($this->getAngle()), 'w' => 0.0],
            'c' => ['x' => 0.0, 'y' => sin($this->getAngle()), 'z' => cos($this->getAngle()), 'w' => 0.0],
            'd' => ['x' => 0.0, 'y' => 0.0, 'z' => 0.0, 'w' => 1.0]
        ];
    }

    private function buildRotYMatrix()
    {
        $this->_matrix = [
            'a' => ['x' => cos($this->getAngle()), 'y' => 0.0, 'z' => sin($this->getAngle()), 'w' => 0.0],
            'b' => ['x' => 0.0, 'y' => 1.0, 'z' => 0.0, 'w' => 0.0],
            'c' => ['x' => -sin($this->getAngle()), 'y' => 0.0, 'z' => cos($this->getAngle()), 'w' => 0.0],
            'd' => ['x' => 0.0, 'y' => 0.0, 'z' => 0.0, 'w' => 1.0]
        ];
    }

    private function buildRotZMatrix()
    {
        $this->_matrix = [
            'a' => ['x' => cos($this->getAngle()), 'y' => -sin($this->getAngle()), 'z' => 0.0, 'w' => 0.0],
            'b' => ['x' => sin($this->getAngle()), 'y' => cos($this->getAngle()), 'z' => 0.0, 'w' => 0.0],
            'c' => ['x' => 0.0, 'y' => 0.0, 'z' => 1.0, 'w' => 0.0],
            'd' => ['x' => 0.0, 'y' => 0.0, 'z' => 0.0, 'w' => 1.0]
        ];
    }

    private function buildTransMatrix()
    {
        $this->_matrix = [
            'a' => ['x' => 1.0, 'y' => 0.0, 'z' => 0.0, 'w' => $this->getVtc()->getX()],
            'b' => ['x' => 0.0, 'y' => 1.0, 'z' => 0.0, 'w' => $this->getVtc()->getY()],
            'c' => ['x' => 0.0, 'y' => 0.0, 'z' => 1.0, 'w' => $this->getVtc()->getZ()],
            'd' => ['x' => 0.0, 'y' => 0.0, 'z' => 0.0, 'w' => 1.0]
        ];
    }

    private function buildProjMatrix()
    {
        $this->_matrix = [
            'a' => ['x' => 1.0 / ($this->getRatio() * tan(deg2rad($this->getFov() / 2))), 'y' => 0.0, 'z' => 0.0, 'w' => 0.0],
            'b' => ['x' => 0.0, 'y' => 1.0 / (tan(deg2rad($this->getFov() / 2))), 'z' => 0.0, 'w' => 0.0],
            'c' => ['x' => 0.0, 'y' => 0.0, 'z' => -(-$this->getNear() - $this->getFar()) / ($this->getNear() - $this->getFar()), 'w' => (2 * $this->getFar() * $this->getNear()) / ($this->getNear() - $this->getFar())],
            'd' => ['x' => 0.0, 'y' => 0.0, 'z' => -1.0, 'w' => 0.0]
        ];
    }

    public function __construct($kwargs)
    {
        $this->_preset = $kwargs['preset'];
        if ($this->getPreset() == Matrix::IDENTITY)
            $this->buildIdentityMatrix();
        else if ($this->getPreset() == Matrix::SCALE) {
            $this->_scale = $kwargs['scale'];
            $this->buildScaleMatrix();
        }
        else if ($this->getPreset() == Matrix::RX || $this->getPreset() == Matrix::RY || $this->getPreset() == Matrix::RZ) {
            $this->_angle = $kwargs['angle'];
            $this->getPreset() == Matrix::RX ? $this->buildRotXMatrix() : ($this->getPreset() == Matrix::RY ? $this->buildRotYMatrix() : $this->buildRotZMatrix());
        }
        else if ($this->getPreset() == Matrix::TRANSLATION) {
            $this->_vtc = $kwargs['vtc'];
            $this->buildTransMatrix();
        }
        else if ($this->getPreset() == Matrix::PROJECTION) {
            $this->_fov = $kwargs['fov'];
            $this->_ratio = $kwargs['ratio'];
            $this->_near = $kwargs['near'];
            $this->_far = $kwargs['far'];
            $this->buildProjMatrix();
        }
        if ($this->getPreset() !== null &&self::$verbose == true)
            echo "Matrix " . Matrix::getConstName($this->getPreset()) . ($this->getPreset() == Matrix::IDENTITY ? "" : " preset") . " instance constructed" . PHP_EOL;
    }

    public function __destruct()
    {
        if (self::$verbose == true)
            echo "Matrix instance destructed" . PHP_EOL;
    }

    public function __toString()
    {
        return (sprintf("M | vtcX | vtcY | vtcZ | vtxO\n-----------------------------%s%s%s%s", Matrix::getRow('x', $this->_matrix['a']), Matrix::getRow('y', $this->_matrix['b']), Matrix::getRow('z', $this->_matrix['c']), Matrix::getRow('w', $this->_matrix['d'])));
    }

    public function doc()
    {
        echo file_get_contents("./Matrix.doc.txt");
    }

    public function getPreset() { return ($this->_preset); }
    public function getScale() { return ($this->_scale); }
    public function getAngle() { return ($this->_angle); }
    public function getVtc() { return ($this->_vtc); }
    public function getFov() { return ($this->_fov); }
    public function getRatio() { return ($this->_ratio); }
    public function getNear() { return ($this->_near); }
    public function getFar() { return ($this->_far); }
    public function getMatrix() { return ($this->_matrix); }

    public function setMatrix($m) { $this->_matrix = $m; return; }

    public function mult(Matrix $rhs)
    {
        $tmp = new Matrix( ['preset' => null] );
        $tmp->setMatrix( [
            'a' => [
                'x' => $this->getMatrix()['a']['x'] * $rhs->getMatrix()['a']['x'] + $this->getMatrix()['a']['y'] * $rhs->getMatrix()['b']['x'] + $this->getMatrix()['a']['z'] * $rhs->getMatrix()['c']['x'] + $this->getMatrix()['a']['w'] * $rhs->getMatrix()['d']['x'],
                'y' => $this->getMatrix()['a']['x'] * $rhs->getMatrix()['a']['y'] + $this->getMatrix()['a']['y'] * $rhs->getMatrix()['b']['y'] + $this->getMatrix()['a']['z'] * $rhs->getMatrix()['c']['y'] + $this->getMatrix()['a']['w'] * $rhs->getMatrix()['d']['y'],
                'z' => $this->getMatrix()['a']['x'] * $rhs->getMatrix()['a']['z'] + $this->getMatrix()['a']['y'] * $rhs->getMatrix()['b']['z'] + $this->getMatrix()['a']['z'] * $rhs->getMatrix()['c']['z'] + $this->getMatrix()['a']['w'] * $rhs->getMatrix()['d']['z'],
                'w' => $this->getMatrix()['a']['x'] * $rhs->getMatrix()['a']['w'] + $this->getMatrix()['a']['y'] * $rhs->getMatrix()['b']['w'] + $this->getMatrix()['a']['z'] * $rhs->getMatrix()['c']['w'] + $this->getMatrix()['a']['w'] * $rhs->getMatrix()['d']['w']],
            'b' => [
                'x' => $this->getMatrix()['b']['x'] * $rhs->getMatrix()['a']['x'] + $this->getMatrix()['b']['y'] * $rhs->getMatrix()['b']['x'] + $this->getMatrix()['b']['z'] * $rhs->getMatrix()['c']['x'] + $this->getMatrix()['b']['w'] * $rhs->getMatrix()['d']['x'],
                'y' => $this->getMatrix()['b']['x'] * $rhs->getMatrix()['a']['y'] + $this->getMatrix()['b']['y'] * $rhs->getMatrix()['b']['y'] + $this->getMatrix()['b']['z'] * $rhs->getMatrix()['c']['y'] + $this->getMatrix()['b']['w'] * $rhs->getMatrix()['d']['y'],
                'z' => $this->getMatrix()['b']['x'] * $rhs->getMatrix()['a']['z'] + $this->getMatrix()['b']['y'] * $rhs->getMatrix()['b']['z'] + $this->getMatrix()['b']['z'] * $rhs->getMatrix()['c']['z'] + $this->getMatrix()['b']['w'] * $rhs->getMatrix()['d']['z'],
                'w' => $this->getMatrix()['b']['x'] * $rhs->getMatrix()['a']['w'] + $this->getMatrix()['b']['y'] * $rhs->getMatrix()['b']['w'] + $this->getMatrix()['b']['z'] * $rhs->getMatrix()['c']['w'] + $this->getMatrix()['b']['w'] * $rhs->getMatrix()['d']['w']],
            'c' => [
                'x' => $this->getMatrix()['c']['x'] * $rhs->getMatrix()['a']['x'] + $this->getMatrix()['c']['y'] * $rhs->getMatrix()['b']['x'] + $this->getMatrix()['c']['z'] * $rhs->getMatrix()['c']['x'] + $this->getMatrix()['c']['w'] * $rhs->getMatrix()['d']['x'],
                'y' => $this->getMatrix()['c']['x'] * $rhs->getMatrix()['a']['y'] + $this->getMatrix()['c']['y'] * $rhs->getMatrix()['b']['y'] + $this->getMatrix()['c']['z'] * $rhs->getMatrix()['c']['y'] + $this->getMatrix()['c']['w'] * $rhs->getMatrix()['d']['y'],
                'z' => $this->getMatrix()['c']['x'] * $rhs->getMatrix()['a']['z'] + $this->getMatrix()['c']['y'] * $rhs->getMatrix()['b']['z'] + $this->getMatrix()['c']['z'] * $rhs->getMatrix()['c']['z'] + $this->getMatrix()['c']['w'] * $rhs->getMatrix()['d']['z'],
                'w' => $this->getMatrix()['c']['x'] * $rhs->getMatrix()['a']['w'] + $this->getMatrix()['c']['y'] * $rhs->getMatrix()['b']['w'] + $this->getMatrix()['c']['z'] * $rhs->getMatrix()['c']['w'] + $this->getMatrix()['c']['w'] * $rhs->getMatrix()['d']['w']],
            'd' => [
                'x' => $this->getMatrix()['d']['x'] * $rhs->getMatrix()['a']['x'] + $this->getMatrix()['d']['y'] * $rhs->getMatrix()['b']['x'] + $this->getMatrix()['d']['z'] * $rhs->getMatrix()['c']['x'] + $this->getMatrix()['d']['w'] * $rhs->getMatrix()['d']['x'],
                'y' => $this->getMatrix()['d']['x'] * $rhs->getMatrix()['a']['y'] + $this->getMatrix()['d']['y'] * $rhs->getMatrix()['b']['y'] + $this->getMatrix()['d']['z'] * $rhs->getMatrix()['c']['y'] + $this->getMatrix()['d']['w'] * $rhs->getMatrix()['d']['y'],
                'z' => $this->getMatrix()['d']['x'] * $rhs->getMatrix()['a']['z'] + $this->getMatrix()['d']['y'] * $rhs->getMatrix()['b']['z'] + $this->getMatrix()['d']['z'] * $rhs->getMatrix()['c']['z'] + $this->getMatrix()['d']['w'] * $rhs->getMatrix()['d']['z'],
                'w' => $this->getMatrix()['d']['x'] * $rhs->getMatrix()['a']['w'] + $this->getMatrix()['d']['y'] * $rhs->getMatrix()['b']['w'] + $this->getMatrix()['d']['z'] * $rhs->getMatrix()['c']['w'] + $this->getMatrix()['d']['w'] * $rhs->getMatrix()['d']['w']],
        ] );
        return ($tmp);
    }

    public function transformVertex(Vertex $vtx)
    {
        return (new Vertex( [
            'x' => $this->getMatrix()['a']['x'] * $vtx->getX() + $this->getMatrix()['a']['y'] * $vtx->getY() + $this->getMatrix()['a']['z'] * $vtx->getZ() + $this->getMatrix()['a']['w'] * $vtx->getW(),
            'y' => $this->getMatrix()['b']['x'] * $vtx->getX() + $this->getMatrix()['b']['y'] * $vtx->getY() + $this->getMatrix()['b']['z'] * $vtx->getZ() + $this->getMatrix()['b']['w'] * $vtx->getW(),
            'z' => $this->getMatrix()['c']['x'] * $vtx->getX() + $this->getMatrix()['c']['y'] * $vtx->getY() + $this->getMatrix()['c']['z'] * $vtx->getZ() + $this->getMatrix()['c']['w'] * $vtx->getW(),
            'w' => $this->getMatrix()['d']['x'] * $vtx->getX() + $this->getMatrix()['d']['y'] * $vtx->getY() + $this->getMatrix()['d']['z'] * $vtx->getZ() + $this->getMatrix()['d']['w'] * $vtx->getW(),
        ] ));
    }

}

?>