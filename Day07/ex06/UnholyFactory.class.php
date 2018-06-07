<?php

class UnholyFactory {

    private $_absorbed = Array();
    private $_absorbedName = Array();

    public function absorb( $someone ) {
        if (get_parent_class($someone) === "Fighter") {
            $someoneReflect = new ReflectionClass($someone);
            $full = false;
            foreach ($this->getAbsorbed() as $value) {
                $reflect = new ReflectionClass($value);
                if ($reflect->getName() === $someoneReflect->getName()) {
                    echo "(Factory already absorbed a fighter of type " . $someone->getType() . ")" . PHP_EOL;
                    $full = true;
                }
            }
            if (!$full) {
                $this->_absorbed[] = $someone;
                $this->_absorbedName[] = "$someone";
                echo "(Factory absorbed a fighter of type " . $someone->getType() . ")" . PHP_EOL;
            }
        }
        else
            echo "(Factory can't absorb this, it's not a fighter)" . PHP_EOL;
    }

    public function fabricate( $someone ) {
        $match = -1;
        foreach ($this->getAbsorbedName() as $key => $type) {
            if ("$someone" === $type) {
                $match = $key;
                break;
            }
        }
        if ($match >= 0) {
            echo "(Factory fabricates a fighter of type $someone)" . PHP_EOL;
            return $this->_absorbed[$key];
        }
    }

    public function getAbsorbed() { return $this->_absorbed; }
    public function getAbsorbedName() { return $this->_absorbedName; }

}