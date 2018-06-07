<?php

class Tyrion extends Lannister {
    public function sleepWith( $someone ) {
        if (get_parent_class($someone) === "Lannister")
            echo "Not even if I'm drunk !" . PHP_EOL;
        else
            echo "Let's do this." . PHP_EOL;
    }
}
