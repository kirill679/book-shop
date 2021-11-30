<?php

$item = Cleaner::sanitizeItemForCatalog($_POST);

if ( ! Cleaner::validateItemForCatalog($item)) {
    echo FORM_NOT_CORRECT;
    header('Refresh: 2;url=add_item_to_catalog');
    exit;
}

$book = new Book($item);

$res = Eshop::addItemToCatalog($book);

echo $res ? CATALOG_ITEM_ADD_OK : CATALOG_ITEM_ADD_ERR;

header('Refresh: 2;url=add_item_to_catalog');