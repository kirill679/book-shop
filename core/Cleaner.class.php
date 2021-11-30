<?php

class Cleaner
{
    static function str($data): string
    {
        return trim(strip_tags($data));
    }


    static function sanitizeUser(array $data): array
    {
        $login    = $data['login'] ?? '';
        $password = $data['password'] ?? '';
        $email    = $data['email'] ?? '';

        $login    = filter_var(substr($login, 0, 25), FILTER_SANITIZE_STRING);
        $password = substr($password, 0, 25);
        $email    = filter_var(substr($email, 0, 25), FILTER_SANITIZE_EMAIL);

        return ['login' => $login, 'password' => $password, 'email' => $email];
    }

    static function validateUser(array $data): bool
    {
        return $data['login']
            && $data['password']
            && filter_var($data['email'], FILTER_VALIDATE_EMAIL);
    }


    static function sanitizeItemForCatalog(array $item): array
    {
        $title    = $item['title'] ?? '';
        $author   = $item['author'] ?? '';
        $pub_year = $item['pub_year'] ?? '';
        $price    = $item['price'] ?? '';

        $title    = filter_var(substr($title, 0, 50), FILTER_SANITIZE_STRING);
        $author   = filter_var(substr($author, 0, 50), FILTER_SANITIZE_STRING);
        $pub_year = filter_var(intval(substr(strval($pub_year), 0, 4)), FILTER_SANITIZE_NUMBER_INT);
        $price    = filter_var(intval(substr(strval($price), 0, 6)), FILTER_SANITIZE_NUMBER_INT);

        return ['title' => $title, 'author' => $author, 'pub_year' => $pub_year, 'price' => $price];
    }

    static function validateItemForCatalog(array $item): bool
    {
        $yearValidationOptions = [
            'options' => [
                'min_range' => 0,
                'max_range' => 9999,
            ],
        ];

        $priceValidationOptions = [
            'options' => [
                'min_range' => 0,
                'max_range' => 999999,
            ],
        ];

        return $item['title']
            && $item['author']
            && filter_var($item['pub_year'], FILTER_VALIDATE_INT, $yearValidationOptions)
            && filter_var($item['price'], FILTER_VALIDATE_INT, $priceValidationOptions);
    }


    static function sanitizeItemFromBasket(Book $item): Book
    {
        $id       = $item->id ?? '';
        $title    = $item->title ?? '';
        $author   = $item->author ?? '';
        $price    = $item->price ?? '';
        $pub_year = $item->pub_year ?? '';
        $quantity = $item->quantity ?? '';

        $id       = filter_var(intval(substr($id, 0, 4)), FILTER_SANITIZE_NUMBER_INT);
        $title    = filter_var(substr($title, 0, 50), FILTER_SANITIZE_STRING);
        $author   = filter_var(substr($author, 0, 50), FILTER_SANITIZE_STRING);
        $price    = filter_var(substr($price, 0, 6), FILTER_SANITIZE_NUMBER_INT);
        $pub_year = filter_var(substr($pub_year, 0, 4), FILTER_SANITIZE_NUMBER_INT);
        $quantity = filter_var(substr($quantity, 0, 4), FILTER_SANITIZE_NUMBER_INT);

        return new Book(
            [
                'id'       => $id,
                'title'    => $title,
                'author'   => $author,
                'price'    => $price,
                'pub_year' => $pub_year,
                'quantity' => $quantity,
            ]
        );
    }

    static function validateItemFromBasket(Book $item): bool
    {
        return filter_var($item->id, FILTER_VALIDATE_INT)
            && $item->title
            && $item->author
            && filter_var($item->price, FILTER_VALIDATE_INT)
            && filter_var($item->pub_year, FILTER_VALIDATE_INT)
            && filter_var($item->quantity, FILTER_VALIDATE_INT);
    }


    static function sanitizeCustomerData(array $data): array
    {
        $customer = $data['customer'] ?? '';
        $email    = $data['email'] ?? '';
        $phone    = $data['phone'] ?? '';
        $address  = $data['address'] ?? '';

        $customer = filter_var(substr($customer, 0, 50), FILTER_SANITIZE_STRING);
        $email    = filter_var(substr($email, 0, 50), FILTER_SANITIZE_EMAIL);
        $phone    = filter_var(substr($phone, 0, 50), FILTER_SANITIZE_NUMBER_INT);
        $address  = filter_var(substr($address, 0, 50), FILTER_SANITIZE_STRING);

        return ['customer' => $customer, 'email' => $email, 'phone' => $phone, 'address' => $address];
    }

    static function validateCustomerData(array $data): bool
    {
        return $data['customer']
            && filter_var($data['email'], FILTER_VALIDATE_EMAIL)
            && filter_var($data['phone'], FILTER_VALIDATE_INT)
            && $data['address'];
    }
}