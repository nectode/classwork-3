<?php
declare(strict_types=1);

/**
 * Задача 3.1. Логирование через трейт
 */

trait LoggerTrait
{
    /** @var string[] */
    private array $logs = [];

    public function log(string $message): void
    {
        $this->logs[] = '[' . date('Y-m-d H:i:s') . '] ' . $message;
    }

    /** @return string[] */
    public function getLogs(): array
    {
        return $this->logs;
    }

    public function clearLogs(): void
    {
        $this->logs = [];
    }
}

/*
 * Почему трейт удобнее наследования: UserService и OrderService не связаны
 * по смыслу («пользователь» и «заказ» не являются разновидностями
 * какого-то общего «логгера»), а в PHP у класса только один родитель.
 * Трейт даёт общую логику без искусственной иерархии и не занимает
 * единственный слот extends. При этом у каждого класса свой журнал.
 */
class UserService
{
    use LoggerTrait;

    public function register(string $username): void
    {
        // ... здесь была бы реальная регистрация
        $this->log("Зарегистрирован пользователь: {$username}");
    }
}

class OrderService
{
    use LoggerTrait;

    public function createOrder(int $orderId, float $total): void
    {
        // ... здесь было бы реальное создание заказа
        $this->log("Создан заказ №{$orderId} на сумму {$total} руб.");
    }
}

function printLogs(string $title, array $logs): void
{
    echo "--- {$title} ---" . PHP_EOL;
    if ($logs === []) {
        echo '(журнал пуст)' . PHP_EOL;
    }
    foreach ($logs as $line) {
        echo $line . PHP_EOL;
    }
}

// ---------- Демонстрация ----------
$users = new UserService();
$users->register('ivan');
$users->register('anna');

$orders = new OrderService();
$orders->createOrder(101, 2500.50);
$orders->createOrder(102, 990.00);

printLogs('Журнал UserService', $users->getLogs());
printLogs('Журнал OrderService', $orders->getLogs());

$users->clearLogs();
echo PHP_EOL . 'Журнал UserService очищен.' . PHP_EOL;

printLogs('Журнал UserService', $users->getLogs());
printLogs('Журнал OrderService (не изменился)', $orders->getLogs());
