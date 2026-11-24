<?php
declare(strict_types=1);

/**
 * Задача 1.2. Система сотрудников (наследование, абстрактный класс)
 */

/*
 * Employee объявлен как abstract, поэтому new Employee(...) вызовет ошибку:
 * абстрактный класс — это лишь шаблон. В нём есть абстрактный метод
 * calculateSalary() без реализации, и вызывать его у «просто сотрудника»
 * бессмысленно: способ расчёта зарплаты определяют только конкретные
 * наследники (Manager, Developer, Intern).
 */
abstract class Employee
{
    public function __construct(
        protected string $name,
        protected float $baseSalary
    ) {
    }

    abstract public function calculateSalary(): float;

    public function getName(): string
    {
        return $this->name;
    }
}

class Manager extends Employee
{
    // оклад + 20% премия
    public function calculateSalary(): float
    {
        return $this->baseSalary * 1.2;
    }
}

class Developer extends Employee
{
    private int $overtimeHours = 0;

    public function setOvertimeHours(int $hours): void
    {
        $this->overtimeHours = $hours;
    }

    // оклад + часы переработки * 500
    public function calculateSalary(): float
    {
        return $this->baseSalary + $this->overtimeHours * 500;
    }
}

class Intern extends Employee
{
    // 50% от оклада
    public function calculateSalary(): float
    {
        return $this->baseSalary * 0.5;
    }
}

/**
 * @param Employee[] $employees
 */
function printPayroll(array $employees): void
{
    $total = 0.0;
    echo "Имя — Зарплата" . PHP_EOL;
    echo str_repeat('-', 30) . PHP_EOL;
    foreach ($employees as $employee) {
        $salary = $employee->calculateSalary();
        $total += $salary;
        echo $employee->getName() . ' — ' . number_format($salary, 2, '.', ' ') . ' руб.' . PHP_EOL;
    }
    echo str_repeat('-', 30) . PHP_EOL;
    echo 'Общий фонд оплаты труда: ' . number_format($total, 2, '.', ' ') . ' руб.' . PHP_EOL;
}

// ---------- Демонстрация ----------
$developer = new Developer('Иван Петров', 80000);
$developer->setOvertimeHours(10);

$employees = [
    new Manager('Анна Смирнова', 100000),
    $developer,
    new Intern('Олег Сидоров', 40000),
];

printPayroll($employees);
