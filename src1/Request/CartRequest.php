<?php

namespace Request;

class CartRequest
{

    public function __construct(private array $data)
    {}

    public function getProductId(): int
    {
        // БАГ: читали $this->data['productId'] (camelCase), а форма в cart.php отправляет
        // поле с именем "product_id" (snake_case, как input в HTML) — ключ никогда не совпадал,
        // и getProductId() всегда возвращал бы ошибку/null. БЫЛО: 'productId' -> СТАЛО: 'product_id'.
        // ПОЧЕМУ: имя ключа в массиве $_POST задаёт атрибут name="..." в HTML-форме, его и нужно
        // использовать как ключ, а не придуманное отдельно название.
        return (int)($this->data['product_id'] ?? 0);
    }
    public function getAmount(): int
    {
        return (int)($this->data['amount'] ?? 0);
    }

    public function CartValidate(): array
    {
        // БАГ: метод начинался со строки "$this->data = [];" — это стирало все данные запроса
        // ДО того, как их проверить, а дальше сравнивался с 0 сам массив $this->data (что в PHP
        // всегда даёт false), поэтому ни одна ошибка никогда не добавлялась — валидация была
        // полностью нерабочей "заглушкой". БЫЛО: обнуление $this->data и сравнение массива с 0 ->
        // СТАЛО: реальная проверка значений product_id и amount, которые возвращают геттеры выше.
        $errors = [];

        if ($this->getProductId() <= 0) {
            $errors['product_id'] = 'Неверный ID товара';
        }

        if ($this->getAmount() <= 0) {
            $errors['amount'] = 'Количество должно быть больше нуля';
        }

        return $errors;
    }
}