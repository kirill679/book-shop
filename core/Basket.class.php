<?php

class Basket
{
    private static array $basket = [];
    private static int $count = 0;
    private static ?string $orderId = null;

    const NAME = 'BASKET';


    public static function init()
    {
        $basket = $_COOKIE[self::NAME] ?? null;

        if (isset($basket)) {
            self::read($basket);
        } else {
            self::create();
        }
    }

    public static function size(): int
    {
        return self::$count;
    }

    public static function getOrderId(): string
    {
        return self::$orderId;
    }

    private static function create()
    {
        self::$orderId = uniqid();
        self::save();
    }

    private static function save()
    {
        self::$basket['order-id'] = self::$orderId;
        $basket                   = base64_encode(serialize(self::$basket));

        setcookie(
            self::NAME,
            $basket,
            ['expires' => time() + 604800, 'secure' => true, 'httponly' => true, 'samesite' => 'Strict']
        );

        $_SESSION['basket-code'] = $basket;
    }

    private static function read(string $basket)
    {
        self::$basket = unserialize(base64_decode($basket));
        self::$orderId = self::$basket['order-id'];
        unset(self::$basket['order-id']);
        self::$count = count(self::$basket);
    }

    public static function add(string $id)
    {
        self::$basket[$id] = self::quantity($id) + 1;
        self::save();
    }

    public static function remove(string $id)
    {
        if (self::$basket[$id] > 1) {
            self::$basket[$id]--;
        } else {
            unset(self::$basket[$id]);
        }

        self::save();
    }

    public static function get(): Traversable
    {
        return new ArrayIterator(self::$basket);
    }

    public static function checkBasket(): string
    {
        self::$basket['order-id'] = self::$orderId;

        return base64_encode(serialize(self::$basket)) === $_SESSION['basket-code'];
    }

    public static function clear()
    {
        self::$basket = [];
        setcookie(self::NAME);
    }

    public static function quantity(string $id): int
    {
        return self::$basket[$id] ?? 0;
    }
}