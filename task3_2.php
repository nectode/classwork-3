<?php
declare(strict_types=1);

/**
 * Задача 3.2. Разрешение конфликтов трейтов
 */

trait JsonExportTrait
{
    public function export(): string
    {
        // get_object_vars() внутри класса вернёт его свойства (у Product они public)
        return json_encode(get_object_vars($this), JSON_UNESCAPED_UNICODE);
    }

    public function getFormat(): string
    {
        return 'JSON';
    }
}

trait XmlExportTrait
{
    public function export(): string
    {
        $xml = '<product>';
        foreach (get_object_vars($this) as $key => $value) {
            $xml .= "<{$key}>" . htmlspecialchars((string)$value) . "</{$key}>";
        }
        return $xml . '</product>';
    }

    public function getFormat(): string
    {
        return 'XML';
    }
}

trait PriceTrait
{
    public function getPriceWithTax(float $tax): float
    {
        return $this->price * (1 + $tax / 100);
    }
}

class Product
{
    use JsonExportTrait, XmlExportTrait, PriceTrait {
        // export(): основной — JSON, XML под псевдонимом
        JsonExportTrait::export insteadof XmlExportTrait;
        XmlExportTrait::export as exportToXml;

        // getFormat(): основной — JSON, XML под псевдонимом
        JsonExportTrait::getFormat insteadof XmlExportTrait;
        JsonExportTrait::getFormat as getJsonFormat;
        XmlExportTrait::getFormat as getXmlFormat;
    }

    public function __construct(
        public string $name,
        public float $price
    ) {
    }

    /*
     * Приоритет: метод самого класса > метод трейта > метод родителя.
     * Поэтому $product->getFormat() вернёт «Product format» — собственный
     * метод класса переопределяет getFormat() из трейтов (в том числе
     * выбранный через insteadof). Реализации трейтов остаются доступными
     * только под псевдонимами getJsonFormat() и getXmlFormat().
     */
    public function getFormat(): string
    {
        return 'Product format';
    }
}

// ---------- Демонстрация ----------
$product = new Product('Ноутбук', 55000.0);

echo 'JSON: ' . $product->export() . PHP_EOL;
echo 'XML:  ' . $product->exportToXml() . PHP_EOL;
echo PHP_EOL;
echo 'getJsonFormat(): ' . $product->getJsonFormat() . PHP_EOL;
echo 'getXmlFormat():  ' . $product->getXmlFormat() . PHP_EOL;
echo 'getFormat():     ' . $product->getFormat() . PHP_EOL;
echo PHP_EOL;
echo 'Цена с налогом 20%: ' . $product->getPriceWithTax(20.0) . ' руб.' . PHP_EOL;
