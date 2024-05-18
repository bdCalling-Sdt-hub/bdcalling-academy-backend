<?php

class Node {
    private $data;
    private $next;

    public function __construct()
    {
        $this->data = 0;
        $this->next = null;
    }

    public function setData($data): void {
        $this->data = $data;
    }

    public function getData(): int {
        return $this->data;
    }

    public function setNext($next): void
    {
        $this->next = $next;
    }

    public function getNext()
    {
        return $this->next;
    }
}

//class LinkedList {
//    private $head;
//
//    public function __construct(){
//        $this->head = null;
//    }
//}
//
//public function insetAtBack($data): void
//{
//    $new
//}
