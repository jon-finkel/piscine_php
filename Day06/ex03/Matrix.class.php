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
    private $_matrix_a;
    private $_matrix_b;
    private $_matrix_c;
    private $_matrix_d;
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
        $this->_matrix_a = ['x' => 1.0, 'y' => 0.0, 'z' => 0.0, 'w' => 0.0];
        $this->_matrix_b = ['x' => 0.0, 'y' => 1.0, 'z' => 0.0, 'w' => 0.0];
        $this->_matrix_c = ['x' => 0.0, 'y' => 0.0, 'z' => 1.0, 'w' => 0.0];
        $this->_matrix_d = ['x' => 0.0, 'y' => 0.0, 'z' => 0.0, 'w' => 1.0];
    }

    private function buildScaleMatrix()
    {
        $this->_matrix_a = ['x' => $this->_scale, 'y' => 0.0, 'z' => 0.0, 'w' => 0.0];
        $this->_matrix_b = ['x' => 0.0, 'y' => $this->_scale, 'z' => 0.0, 'w' => 0.0];
        $this->_matrix_c = ['x' => 0.0, 'y' => 0.0, 'z' => $this->_scale, 'w' => 0.0];
        $this->_matrix_d = ['x' => 0.0, 'y' => 0.0, 'z' => 0.0, 'w' => 1.0];
    }

    private function buildRotXMatrix()
    {
        $this->_matrix_a = ['x' => 1.0, 'y' => 0.0, 'z' => 0.0, 'w' => 0.0];
        $this->_matrix_b = ['x' => 0.0, 'y' => cos($this->_angle), 'z' => -sin($this->_angle), 'w' => 0.0];
        $this->_matrix_c = ['x' => 0.0, 'y' => sin($this->_angle), 'z' => cos($this->_angle), 'w' => 0.0];
        $this->_matrix_d = ['x' => 0.0, 'y' => 0.0, 'z' => 0.0, 'w' => 1.0];
    }

    private function buildRotYMatrix()
    {
        $this->_matrix_a = ['x' => cos($this->_angle), 'y' => 0.0, 'z' => sin($this->_angle), 'w' => 0.0];
        $this->_matrix_b = ['x' => 0.0, 'y' => 1.0, 'z' => 0.0, 'w' => 0.0];
        $this->_matrix_c = ['x' => -sin($this->_angle), 'y' => 0.0, 'z' => cos($this->_angle), 'w' => 0.0];
        $this->_matrix_d = ['x' => 0.0, 'y' => 0.0, 'z' => 0.0, 'w' => 1.0];
    }

    private function buildRotZMatrix()
    {
        $this->_matrix_a = ['x' => cos($this->_angle), 'y' => -sin($this->_angle), 'z' => 0.0, 'w' => 0.0];
        $this->_matrix_b = ['x' => sin($this->_angle), 'y' => cos($this->_angle), 'z' => 0.0, 'w' => 0.0];
        $this->_matrix_c = ['x' => 0.0, 'y' => 0.0, 'z' => 1.0, 'w' => 0.0];
        $this->_matrix_d = ['x' => 0.0, 'y' => 0.0, 'z' => 0.0, 'w' => 1.0];
    }

    private function buildTransMatrix()
    {
        $this->_matrix_a = ['x' => 1.0, 'y' => 0.0, 'z' => 0.0, 'w' => $this->_vtc->getX()];
        $this->_matrix_b = ['x' => 0.0, 'y' => 1.0, 'z' => 0.0, 'w' => $this->_vtc->getY()];
        $this->_matrix_c = ['x' => 0.0, 'y' => 0.0, 'z' => 1.0, 'w' => $this->_vtc->getZ()];
        $this->_matrix_d = ['x' => 0.0, 'y' => 0.0, 'z' => 0.0, 'w' => 1.0];
    }

    private function buildProjMatrix()
    {
        $this->_matrix_a = ['x' => 1.0 / ($this->_ratio * tan(deg2rad($this->_fov / 2))), 'y' => 0.0, 'z' => 0.0, 'w' => 0.0];
        $this->_matrix_b = ['x' => 0.0, 'y' => 1.0 / (tan(deg2rad($this->_fov / 2))), 'z' => 0.0, 'w' => 0.0];
        $this->_matrix_c = ['x' => 0.0, 'y' => 0.0, 'z' => -(-$this->_near - $this->_far) / ($this->_near - $this->_far), 'w' => (2 * $this->_far * $this->_near) / ($this->_near - $this->_far)];
        $this->_matrix_d = ['x' => 0.0, 'y' => 0.0, 'z' => -1.0, 'w' => 0.0];
    }

    private function buildMultMatrix($rhs)
    {
        $this->_matrix_a = ['x' => 5, 'y' => 5, 'z' => 5, 'w' => 5];
        $this->_matrix_b = ['x' => 5, 'y' => 5, 'z' => 5, 'w' => 5];
        $this->_matrix_c = ['x' => 5, 'y' => 5, 'z' => 5, 'w' => 5];
        $this->_matrix_d = ['x' => 5, 'y' => 5, 'z' => 5, 'w' => 5];
    }

    public function __construct($kwargs)
    {
        $this->_preset = $kwargs['preset'];
        if ($this->_preset == Matrix::IDENTITY)
            $this->buildIdentityMatrix();
        else if ($this->_preset == Matrix::SCALE) {
            $this->_scale = $kwargs['scale'];
            $this->buildScaleMatrix();
        }
        else if ($this->_preset == Matrix::RX || $this->_preset == Matrix::RY || $this->_preset == Matrix::RZ) {
            $this->_angle = $kwargs['angle'];
            $this->_preset == Matrix::RX ? $this->buildRotXMatrix() : ($this->_preset == Matrix::RY ? $this->buildRotYMatrix() : $this->buildRotZMatrix());
        }
        else if ($this->_preset == Matrix::TRANSLATION) {
            $this->_vtc = $kwargs['vtc'];
            $this->buildTransMatrix();
        }
        else if ($this->_preset == Matrix::PROJECTION) {
            $this->_fov = $kwargs['fov'];
            $this->_ratio = $kwargs['ratio'];
            $this->_near = $kwargs['near'];
            $this->_far = $kwargs['far'];
            $this->buildProjMatrix();
        }
        else
            $this->buildMultMatrix($kwargs['matrix']);
        $this->_matrix = ['a' => $this->_matrix_a, 'b' => $this->_matrix_b, 'c' => $this->_matrix_c, 'd' => $this->_matrix_d];
        if (self::$verbose == true)
            echo "Matrix " . Matrix::getConstName($this->_preset) . ($this->_preset == Matrix::IDENTITY ? "" : " preset") . " instance constructed" . PHP_EOL;
    }

    public function __destruct()
    {
        if (self::$verbose == true)
            echo "Matrix instance destructed" . PHP_EOL;
    }

    public function __toString()
    {
        return (sprintf("M | vtcX | vtcY | vtcZ | vtxO\n-----------------------------%s%s%s%s", Matrix::getRow('x', $this->_matrix_a), Matrix::getRow('y', $this->_matrix_b), Matrix::getRow('z', $this->_matrix_c), Matrix::getRow('w', $this->_matrix_d)));
    }

    public function doc()
    {
        echo file_get_contents("./Matrix.doc.txt");
    }

    public function mult(Matrix $rhs)
    {
        return (new Matrix( ['matrix' => $rhs] ));
    }

}

?>