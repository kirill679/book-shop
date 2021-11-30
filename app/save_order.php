<?php

if ( ! Basket::size()) {
    echo SHOW_BASKET_EMPTY;
    header('Refresh: 2;url=basket');
    exit;
}

if ( ! Basket::checkBasket()) {
    echo ORDER_BASKET_ERR;
    Basket::clear();
    header('Refresh: 2;url=basket');
    exit;
}

$customerData = Cleaner::sanitizeCustomerData($_POST);

if ( ! Cleaner::validateCustomerData($customerData)) {
    echo FORM_NOT_CORRECT;
    header('Refresh: 2;url=create_order');
    exit;
}

$items = Eshop::getItemsFromBasket();

foreach ($items as $item) {
    $book = Cleaner::sanitizeItemFromBasket($item);
    if ( ! Cleaner::validateItemFromBasket($book)) {
        echo ORDER_BASKET_ERR;
        header('Refresh: 2;url=basket');
        exit;
    }
}

$items = ['items' => iterator_to_array($items)];

$orderData = array_merge($items, $customerData);

$order = new Order($orderData);

if (Eshop::saveOrder($order)) {
    echo ORDER_SAVE_OK;
    header('Refresh: 2;url=catalog');
    exit;
}

echo ORDER_SAVE_ERR;
header('Refresh: 2;url=create_order');