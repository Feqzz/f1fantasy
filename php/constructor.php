<?php


class constructor
{
    public function __construct($constructorId, $name, $nationality)
    {
        $this->constructorId = $constructorId;
        $this->name = $name;
        $this->nationality = $nationality;
    }

    public function getConstructorId()
    {
        return $this->constructorId;
    }
    private $constructorId;
    private $name;
    private $nationality;
}