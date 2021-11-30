<?php

$books = Eshop::getItemsFromCatalog();

if ( ! ($books instanceof Iterator)) {
    echo CATALOG_SHOW_ERR;
    throw new Exception('Exception :(');
}
if ($books instanceof EmptyIterator) {
    echo CATALOG_SHOW_EMPTY;
} else {
    ?>
    <p class='admin'><a href='/admin'>Админка</a></p>
    <h1>Каталог товаров</h1>
    <p>Товаров в <a href='/basket'>корзине</a>: <?= Eshop::countItemsInBasket() ?></p>
    <table>
        <tr>
            <th>Название</th>
            <th>Автор</th>
            <th>Год издания</th>
            <th>Цена, руб.</th>
            <th>В корзину</th>
        </tr>
        <?php
        foreach ($books as $book) {
            ?>
            <tr>
                <td><?= $book->title ?></td>
                <td><?= $book->author ?></td>
                <td><?= $book->pub_year ?></td>
                <td><?= $book->price ?></td>
                <td><a href="/add_item_to_basket?id=<?= $book->id ?>">В корзину</a></td>
            </tr>
            <?php
        }
        ?>
    </table>

    <?php
}

?>