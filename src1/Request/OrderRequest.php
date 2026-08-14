<?php

namespace Request;

class OrderRequest
{
    public function __construct(private array $data)
    {}

    public function getContactName(): string
    {
        return $this->data['contact_name'];
    }
    public function getContactPhone(): string
    {
        return $this->data['contact_phone'];
    }
    public function getAddress(): string
    {
        return $this->data['address'];
    }
    public function getComment(): string
    {
        return $this->data['comment'];
    }

    public function validate(): array
    {
        $errors = [];

        $contactName = $this->validateName();
        if (!empty($contactName)) {
            $errors['contact_name'] = $contactName;
        }


        if (isset($this->data['contact_phone'])) {
            $contactPhone = $this->data['contact_phone'];

            if (!ctype_digit($contactPhone)) {
                $errors['contact_phone'] = 'Телефон должен содержать только цифры';
            } elseif (strlen($contactPhone) < 10) {
                $errors['contact_phone'] = 'Телефон должен содержать минимум 10 цифр';
            } elseif (strlen($contactPhone) > 15) {
                $errors['contact_phone'] = 'Телефон не может содержать больше 15 цифр';
            }
        } else {
            $errors['contact_phone'] = 'Телефон должен быть заполнен!';
        }
        // Валидация комментария
        if (isset($this->data['comment']) && !empty($this->data['comment'])) {
            $comment = $this->data['comment'];
            if (strlen($comment) > 500) {
                $errors['comment'] = 'Комментарий не может быть длиннее 500 символов';
            }
        }

        if (isset($this->data['address'])) {
            $address = $this->data['address'];
            if (strlen($address) < 5) {
                $errors['address'] = 'Адрес не может содержать меньше 5 символов';
            }
        } else {
            $errors['address'] = 'Адрес должен быть заполнен!';
        }

        return $errors;
    }

    private function validateName(): null|string
    {
        if (isset($this->data['contact_name'])) {
            $contact_name = $this->data['contact_name'];
            if (strlen($contact_name) < 3) {
                return 'Имя не может содержать меньше 3 символов';
            }
            return null;
        } else {
            return 'Имя должно быть заполнено';
        }
    }
}