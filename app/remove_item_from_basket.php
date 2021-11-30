<?php

$id  = $_GET['id'];
$res = Eshop::removeItemFromBasket($id);

if ($res) {
    header('Location: basket');
} else {
    echo BASKET_ITEM_REMOVE_ERR;
    header('Refresh: 2;url=basket');
}