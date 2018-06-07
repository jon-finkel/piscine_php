<?php

class Targaryen {
    public function getBurned() {
        return (!$this->resistsFire() ? "burns alive" : "emerges naked but unharmed");
    }
    public function resistsFire() {
        return false;
    }
}
