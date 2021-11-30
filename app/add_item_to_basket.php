<?php

if (isset($_GET['id'])) {
    $res = Eshop::addItemToBasket($_GET['id']);

    if ($res) {
        echo BASKET_ADD_OK;
        header('Refresh: 1;url=catalog');
        exit;
    }
}

echo BASKET_ITEM_ADD_ERR;
header('Refresh: 2;url=catalog');
