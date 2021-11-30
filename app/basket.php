<?php

$books = Eshop::getItemsFromBasket();

if ( ! ($books instanceof Iterator)) {
    echo BASKET_SHOW_ERR;
    throw new Exception('Exception :(');
}

function getTotalPrice(): int
{
    global $books;
    $totalPrice = 0;

    foreach ($books as $book) {
        $totalPrice += $book->price * $book->quantity;
    }

    return $totalPrice;
}

?>

    <p>Вернуться в <a href='/catalog'>каталог</a></p>
    <h1>Ваша корзина</h1>
<?php
if ($books instanceof EmptyIterator) {
    echo SHOW_BASKET_EMPTY;
} else {
    ?>
    <table>
        <tr>
            <th>N п/п</th>
            <th>Название</th>
            <th>Автор</th>
            <th>Год издания</th>
            <th>Цена, руб.</th>
            <th>Количество</th>
            <th>Удалить</th>
        </tr>
        <?php
        foreach ($books as $key => $book) { ?>
            <tr>
                <td><?= $key + 1 ?></td>
                <td><?= $book->title ?></td>
                <td><?= $book->author ?></td>
                <td><?= $book->pub_year ?></td>
                <td><?= $book->price ?></td>
                <td><?= $book->quantity ?></td>
                <td><a href="/remove_item_from_basket?id=<?= $book->id ?>">Удалить</a></td>
            </tr>
            <?php
        } ?>
    </table>

    <p>Всего товаров в корзине на сумму: <?= getTotalPrice() ?> руб.</p>

    <div class="text-center">
        <button onclick="location.href='/create_order'">Оформить заказ!</button>
    </div>

    <?php
} ?>