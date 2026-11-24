<?php
declare(strict_types=1);

/**
 * Задача 2.2. Форматирование отчётов (интерфейсы)
 */

interface ReportFormatterInterface
{
    public function format(array $data): string;
}

interface ReportHeaderInterface
{
    public const SEPARATOR = '=====';

    public function getHeader(): string;
}

class HtmlFormatter implements ReportFormatterInterface
{
    public function format(array $data): string
    {
        $items = '';
        foreach ($data as $item) {
            $items .= '<li>' . $item . '</li>';
        }
        return '<ul>' . $items . '</ul>';
    }
}

class CsvFormatter implements ReportFormatterInterface
{
    public function format(array $data): string
    {
        return implode(';', $data);
    }
}

class SalesReport implements ReportFormatterInterface, ReportHeaderInterface
{
    /** @param array<string, float> $sales продукт => сумма */
    public function __construct(private array $sales)
    {
    }

    public function getHeader(): string
    {
        return 'Отчёт о продажах за текущий период';
    }

    // Если данные не переданы, берём собственные продажи
    public function format(array $data = []): string
    {
        $data = $data ?: $this->sales;
        $lines = [];
        foreach ($data as $product => $sum) {
            $lines[] = "Продукт: {$product} — сумма: {$sum} руб.";
        }
        return implode(PHP_EOL, $lines);
    }
}

function renderReport(ReportFormatterInterface $report, array $data = []): void
{
    // Заголовок выводим, только если объект реализует и второй интерфейс
    if ($report instanceof ReportHeaderInterface) {
        echo ReportHeaderInterface::SEPARATOR . ' ' . $report->getHeader()
            . ' ' . ReportHeaderInterface::SEPARATOR . PHP_EOL;
    }
    echo $report->format($data) . PHP_EOL;
}

// ---------- Демонстрация ----------
$items = ['Яблоки', 'Груши', 'Сливы'];

echo 'HTML: ';
renderReport(new HtmlFormatter(), $items);
echo 'CSV: ';
renderReport(new CsvFormatter(), $items);
echo PHP_EOL;

$sales = new SalesReport(['Ноутбук' => 55000, 'Мышь' => 1200, 'Монитор' => 18000]);

// Один и тот же объект передаётся как ReportFormatterInterface
renderReport($sales);

// ...а заголовок получаем через ReportHeaderInterface
function printHeader(ReportHeaderInterface $report): void
{
    echo PHP_EOL . 'Заголовок через ReportHeaderInterface: ' . $report->getHeader() . PHP_EOL;
}
printHeader($sales);

echo 'Константа SEPARATOR: ' . ReportHeaderInterface::SEPARATOR . PHP_EOL;
