<?php
declare(strict_types=1);

/**
 * Задача 1.1. Иерархия транспортных средств (наследование)
 */

class Vehicle
{
    public function __construct(
        protected string $brand,
        protected int $maxSpeed
    ) {
    }

    public function getInfo(): string
    {
        return "Марка: {$this->brand}, макс. скорость: {$this->maxSpeed} км/ч";
    }
}

class Car extends Vehicle
{
    private string $fuelType;

    public function __construct(string $brand, int $maxSpeed, string $fuelType)
    {
        parent::__construct($brand, $maxSpeed); // конструктор родителя
        $this->fuelType = $fuelType;
    }

    // Переопределение: дополняем строку родителя
    public function getInfo(): string
    {
        return parent::getInfo() . ", тип топлива: {$this->fuelType}";
    }
}

class Airplane extends Vehicle
{
    private float $wingspan;

    public function __construct(string $brand, int $maxSpeed, float $wingspan)
    {
        parent::__construct($brand, $maxSpeed);
        $this->wingspan = $wingspan;
    }

    public function getInfo(): string
    {
        return parent::getInfo() . ", размах крыльев: {$this->wingspan} м";
    }
}

// ---------- Демонстрация ----------
$vehicles = [
    new Car('Toyota', 200, 'бензин'),
    new Airplane('Boeing 737', 850, 35.8),
];

foreach ($vehicles as $vehicle) {
    echo $vehicle->getInfo() . PHP_EOL;
}

echo PHP_EOL . "Проверка instanceof:" . PHP_EOL;
foreach ($vehicles as $vehicle) {
    echo get_class($vehicle) . ' instanceof Vehicle: '
        . ($vehicle instanceof Vehicle ? 'true' : 'false') . PHP_EOL;
}
