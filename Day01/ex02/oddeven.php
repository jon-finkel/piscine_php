#!/usr/bin/php
<?php
    while (101010)
    {
        echo "Entrez un nombre: ";
        $value = rtrim(fgets(STDIN));
        if (feof(STDIN) === true)
            break;
        else if ($value > PHP_INT_MAX)
            echo "Overflow\n";
        else if (!is_numeric($value))
            printf("'%s' n'est pas un chiffre\n", $value);
        else if ($value % 2)
            printf("Le chiffre %s est Impair\n", $value);
        else
            printf("Le chiffre %s est Pair\n", $value);
    }
    echo "\n";
?>
