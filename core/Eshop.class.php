<?php

class Eshop
{
    private static ?PDO $db = null;


    public static function init(array $dbConfig)
    {
        $host     = $dbConfig['HOST'];
        $dbName   = $dbConfig['NAME'];
        $user     = $dbConfig['USER'];
        $password = $dbConfig['PASS'];

        self::$db = new PDO("mysql:host=$host;dbname=$dbName", $user, $password);
        self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }


    public static function addItemToCatalog(Book $item): bool
    {
        $stmt = self::$db->prepare('CALL spAddItemToCatalog(:title, :author, :price, :pub_year)');
        $stmt->bindValue(':title', $item->title);
        $stmt->bindValue(':author', $item->author);
        $stmt->bindValue(':price', $item->price);
        $stmt->bindValue(':pub_year', $item->pub_year);

        return $stmt->execute();
    }

    public static function getItemsFromCatalog(): Traversable
    {
        $sql   = "CALL spGetCatalog()";
        $stmt  = self::$db->query($sql);
        $books = $stmt->fetchAll(PDO::FETCH_CLASS, 'Book');

        if ($books) {
            return new ArrayIterator($books);
        } else {
            return new EmptyIterator();
        }
    }

    private static function itemExists(string $id): bool
    {
        $stmt = self::$db->prepare("CALL spGetItemById(:itemId)");
        $stmt->bindValue(':itemId', $id);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }


    public static function countItemsInBasket(): int
    {
        return Basket::size();
    }

    public static function addItemToBasket(string $id): bool
    {
        if (self::itemExists($id)) {
            Basket::add($id);

            return true;
        } else {
            return false;
        }
    }

    public static function removeItemFromBasket(string $id): bool
    {
        if (self::itemExists($id)) {
            Basket::remove($id);

            return true;
        } else {
            return false;
        }
    }

    public static function getItemsFromBasket(): Traversable
    {
        if ( ! self::countItemsInBasket()) {
            return new EmptyIterator();
        }

        $keys = array_keys(iterator_to_array(Basket::get()));
        $ids  = implode(',', $keys);

        $sql  = "CALL spGetItemsForBasket('$ids')";
        $stmt = self::$db->query($sql);

        $books = $stmt->fetchAll(PDO::FETCH_CLASS, 'Book');
        foreach ($books as $book) {
            $book->quantity = Basket::quantity($book->id);
        }

        return new ArrayIterator($books);
    }


    public static function saveOrder(Order $order): bool
    {
        try {
            self::$db->beginTransaction();

            $stmt = self::$db->prepare('CALL spSaveOrder(:orderId, :customer, :email, :phone, :address)');
            $stmt->bindValue(':orderId', Basket::getOrderId());
            $stmt->bindValue(':customer', $order->customer);
            $stmt->bindValue(':email', $order->email);
            $stmt->bindValue(':phone', $order->phone);
            $stmt->bindValue(':address', $order->address);

            $stmt->execute();

            $stmt = self::$db->prepare('CALL spSaveOrderedItems(:orderId, :itemId, :quantity)');
            $stmt->bindValue(':orderId', Basket::getOrderId());
            $stmt->bindParam(':itemId', $itemId);
            $stmt->bindParam(':quantity', $quantity);

            foreach ($order->items as $item) {
                $itemId   = $item->id;
                $quantity = $item->quantity;

                $stmt->execute();
            }

            Basket::clear();

            return self::$db->commit();
        } catch (Throwable $e) {
            echo $e;
            self::$db->rollBack();

            return false;
        }
    }

    public static function getOrders(): Traversable
    {
        $sql  = 'CALL spGetOrders()';
        $stmt = self::$db->query($sql);

        $orders = $stmt->fetchAll(PDO::FETCH_CLASS, 'Order');

        if ( ! count($orders)) {
            return new EmptyIterator();
        }

        $stmt->closeCursor();

        $stmt = self::$db->prepare('CALL spGetOrderedItems(:orderId)');
        $stmt->bindParam(':orderId', $orderId);

        foreach ($orders as $order) {
            $orderId = $order->id;

            $stmt->execute();
            $order->items = $stmt->fetchAll(PDO::FETCH_CLASS, 'Book');

            $stmt->closeCursor();
        }

        return new ArrayIterator($orders);
    }


    public static function addUser(User $user): bool
    {
        if (self::checkUserExists($user->login)) {
            return false;
        }

        $stmt = self::$db->prepare('CALL spSaveAdmin(:login, :password, :email)');
        $stmt->bindValue(':login', Cleaner::str($user->login));
        $stmt->bindValue(':password', self::createHash($user->password));
        $stmt->bindValue(':email', Cleaner::str($user->email));

        return $stmt->execute();
    }

    public static function createHash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public static function checkUserExists(string $login): bool
    {
        $stmt = self::$db->prepare('CALL spGetAdmin(:login)');
        $stmt->bindValue(':login', $login);

        $stmt->execute();

        return boolval($stmt->fetch());
    }

    public static function checkUser(User $user): bool
    {
        $userFromDB = self::getUserByLogin($user->login);

        return password_verify($user->password, $userFromDB->password);
    }

    public static function getUserByLogin(string $login): User
    {
        $stmt = self::$db->prepare('CALL spGetAdmin(:login)');
        $stmt->bindValue(':login', $login);

        $stmt->execute();

        $user            = $stmt->fetch(PDO::FETCH_ASSOC);
        $user['created'] = strtotime($user['created']);

        return new User($user);
    }

    public static function isAdmin(): bool
    {
        if (isset($_SESSION['admin'])) {
            if ($_SESSION['admin'] === true) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function logIn()
    {
        $_SESSION['admin'] = true;
    }

    public static function logOut()
    {
        session_destroy();
    }
}