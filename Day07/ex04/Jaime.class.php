<?php

class Jaime extends Lannister {
    public function sleepWith( $someone ) {
        $reflect = new ReflectionClass($someone);
        if ($reflect->getName() === "Cersei")
            echo "With pleasure, but only in a tower in Winterfell, then." . PHP_EOL;
        else if (get_parent_class($someone) !== "Lannister")
            echo "Let's do this." . PHP_EOL;
        else
            echo "Not even if I'm drunk !" . PHP_EOL;
    }
}
