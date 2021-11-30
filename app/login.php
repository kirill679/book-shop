<?php

if ( ! Eshop::checkUserExists($_POST['login'])) {
    echo USER_LOGIN_ERR;
    header('Refresh: 2;url=enter');
    exit;
}

$user = new User($_POST);

if (Eshop::checkUser($user)) {
    Eshop::logIn();
    header('Location: admin');
} else {
    echo USER_LOGIN_ERR;
    header('Refresh: 2;url=enter');
}