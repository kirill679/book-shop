<?php

$user = Cleaner::sanitizeUser($_POST);

if ( ! Cleaner::validateUser($user)) {
    echo FORM_NOT_CORRECT;
    header('Refresh: 2;url=create_user');
    exit;
}

$user = new User($user);
$res  = Eshop::addUser($user);

echo $res ? USER_ADD_OK : USER_ADD_ERR;

header('Refresh: 2;url=create_user');