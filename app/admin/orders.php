<?php

date_default_timezone_set('Europe/Moscow');

$orders = Eshop::getOrders();
if ( ! ($orders instanceof Iterator)) {
    echo ORDERS_SHOW_ERR;
    throw new Exception('Exception :(');
}

function getTotalBasketPrice(array $items): int
{
    $totalPrice = 0;

    foreach ($items as $item) {
        $totalPrice += $item->price * $item->quantity;
    }

    return $totalPrice;
}

?>

    <h1>Поступившие заказы:</h1>
    <a href='/admin'>Назад в админку</a>
    <hr>
<?php
if ($orders instanceof EmptyIterator) {
    echo ORDERS_SHOW_EMPTY;
} else {
    foreach ($orders as $order) {
        ?>
        <h2>Заказ номер: <?= $order->id ?></h2>
        <p><b>Заказчик</b>: <?= $order->customer ?></p>
        <p><b>Email</b>: <?= $order->email ?></p>
        <p><b>Телефон</b>: <?= $order->phone ?></p>
        <p><b>Адрес доставки</b>: <?= $order->address ?></p>
        <p><b>Дата размещения заказа</b>: <?= date('j.n.Y, H:i:s', $order->created) ?></p>

        <h3>Купленные товары:</h3>
        <table>
            <tr>
                <th>N п/п</th>
                <th>Название</th>
                <th>Автор</th>
                <th>Год издания</th>
                <th>Цена, руб.</th>
                <th>Количество</th>
            </tr>
            <?php
            foreach ($order->items as $key => $item) {
                ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $item->title ?></td>
                    <td><?= $item->author ?></td>
                    <td><?= $item->pub_year ?></td>
                    <td><?= $item->price ?></td>
                    <td><?= $item->quantity ?></td>
                </tr>
                <?php
            }
            ?>
        </table>
        <p>Всего товаров в заказе на сумму: <?= getTotalBasketPrice($order->items) ?> руб.</p>
        <br>
        <?php
    }
}
?>