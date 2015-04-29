<?php
/*
 * Smarty plugin
* -------------------------------------------------------------
* Fichier :  function.eightball.php
* Type :     fonction
* Nom :      eightball
* Rôle :     renvoie une phrase magique au hasard
* -------------------------------------------------------------
*/
function smarty_function_urlfor($params, &$smarty)
{
    $slim = $smarty->tpl_vars['slim']->value;

    if (isset($params['routeName'])) {
        $routeName = $params['routeName'];
        unset($params['routeName']);
    } else {
        $routeName = 'default';
    }
    return $slim->urlFor($routeName, $params);
}
