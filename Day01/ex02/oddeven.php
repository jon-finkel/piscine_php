#!/usr/bin/php
<?php
    while (101010)
    {
        printf("Entrez un nombre: ");
        $value = rtrim(fgets(STDIN));
        if ($value > PHP_INT_MAX)
            printf("FROMAGE\n");
        else if (!is_numeric($value))
            printf("'%s' n'est pas un chiffre\n", $value);
        else if ($value % 2)
            printf("Le chiffre %s est Impair\n", $value);
        else
            printf("Le chiffre %s est Pair\n", $value);
    }
?>
