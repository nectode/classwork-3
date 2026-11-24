<?php
declare(strict_types=1);

/**
 * Задача 2.1. Способы оплаты (интерфейсы)
 */

interface PaymentInterface
{
    public function pay(float $amount): bool;

    public function refund(float $amount): bool;

    public function getMethodName(): string;
}

class CreditCard implements PaymentInterface
{
    public function __construct(private float $limit)
    {
    }

    // успешно, если сумма не превышает лимит
    public function pay(float $amount): bool
    {
        return $amount > 0 && $amount <= $this->limit;
    }

    public function refund(float $amount): bool
    {
        return $amount > 0;
    }

    public function getMethodName(): string
    {
        return 'Кредитная карта';
    }
}

class PayPal implements PaymentInterface
{
    public function __construct(private float $balance)
    {
    }

    // успешно, если на балансе хватает средств; баланс уменьшается
    public function pay(float $amount): bool
    {
        if ($amount <= 0 || $amount > $this->balance) {
            return false;
        }
        $this->balance -= $amount;
        return true;
    }

    // баланс увеличивается
    public function refund(float $amount): bool
    {
        if ($amount <= 0) {
            return false;
        }
        $this->balance += $amount;
        return true;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getMethodName(): string
    {
        return 'PayPal';
    }
}

class Cash implements PaymentInterface
{
    public function pay(float $amount): bool
    {
        return true;
    }

    public function refund(float $amount): bool
    {
        return true;
    }

    public function getMethodName(): string
    {
        return 'Наличные';
    }
}

// Принимает любой объект, реализующий PaymentInterface
function checkout(PaymentInterface $method, float $amount): void
{
    $ok = $method->pay($amount);
    echo sprintf(
        "[%s] оплата %.2f руб.: %s",
        $method->getMethodName(),
        $amount,
        $ok ? 'успешно' : 'отказ'
    ) . PHP_EOL;
}

// ---------- Демонстрация ----------
$card = new CreditCard(50000);
checkout($card, 30000);   // успешно
checkout($card, 70000);   // превышен лимит

$paypal = new PayPal(10000);
checkout($paypal, 4000);  // успешно, баланс 6000
checkout($paypal, 8000);  // недостаточно средств
$paypal->refund(4000);
echo 'Баланс PayPal после возврата: ' . $paypal->getBalance() . PHP_EOL;

$cash = new Cash();
checkout($cash, 1500);    // всегда успешно
checkout($cash, 999999);  // всегда успешно
echo 'Неуспешная оплата наличными невозможна: Cash::pay() всегда возвращает true.' . PHP_EOL;
