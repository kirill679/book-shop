<?php

class Order
{
    public ?int $created;
    public array $items;
    public string $customer;
    public string $email;
    public string $phone;
    public string $address;

    public function __construct(array $order = [])
    {
        if ($order) {
            $this->created  = $order['created'] ?? null;
            $this->items    = $order['items'] ?? [];
            $this->customer = $order['customer'];
            $this->email    = $order['email'];
            $this->phone    = $order['phone'];
            $this->address  = $order['address'];
        }
    }
}