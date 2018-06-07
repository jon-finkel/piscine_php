<?php

class NightsWatch {
    private $_recruits = Array();

    public function recruit( $someone ) {
        $reflect = new ReflectionClass($someone);
        $interfaces = $reflect->getInterfaces();
        foreach ($interfaces as $key => $value) {
            if ($key === "IFighter") {
                $this->_recruits[] = $someone;
                break;
            }
        }
    }
    public function fight() {
        foreach ($this->_recruits as $recruit) {
            $recruit->fight();
        }
    }
}