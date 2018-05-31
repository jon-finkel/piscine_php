<?php

function auth($login, $passwd)
{
    $path = '/Users/nfinkel/42/MAMP/apache2/htdocs/private/passwd';
    if (isset($login) && isset($passwd) && file_exists($path)) {
        $file = unserialize(file_get_contents($path));
        foreach ($file as $key => $value) {
            if ($value['login'] === $login && $value['passwd'] === hash(whirlpool, $passwd))
                return true;
        }
    }
    return false;
}

?>
