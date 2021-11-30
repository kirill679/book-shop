<?php

class Book
{
    public ?int $id;
    public string $title;
    public string $author;
    public int $price;
    public int $pub_year;
    public ?int $quantity;

    public function __construct(array $item = [])
    {
        if ($item) {
            $this->id       = $item['id'] ?? null;
            $this->title    = $item['title'];
            $this->author   = $item['author'];
            $this->price    = $item['price'];
            $this->pub_year = $item['pub_year'];
            $this->quantity = $item['quantity'] ?? null;
        }
    }
}