<?php

namespace Model;


class Order extends Model
{
    private int $id;
    private string $contactName;
    private int $contactPhone;
    private string $comment;
    private string $address;
    private int $userId;
    public array $OrderProducts = [];
    public float $total = 0;

    protected function getTableName(): string
    {
        return "orders";
    }

    public function getAllByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE user_id = :userId ORDER BY id DESC");
        $stmt->execute(['userId' => $userId]);
        $ordersData = $stmt->fetchAll();

        $result = [];
        foreach ($ordersData as $data) {
            $obj = new self();
            $obj->id = $data['id'];
            $obj->contactName = $data['contact_name'];
            $obj->contactPhone = $data['contact_phone'];
            $obj->comment = $data['comment'];
            $obj->address = $data['address'];
            $obj->userId = $data['user_id'];
            $result[] = $obj;
        }
        return $result;
    }


    public function create(

        string $contactName,
        string $contactPhone,
        string $comment,
        string $address,
        int $userId): self|null

    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO {$this->getTableName()} (contact_name, contact_phone, comment, address, user_id) 
                   VALUES (:name, :phone, :comment, :address, :user_id) RETURNING id"
        );

        $stmt->execute([
            'name' => $contactName,
            'phone' => $contactPhone,
            'comment' => $comment,
            'address' => $address,
            'user_id' => $userId
        ]);

        $data = $stmt->fetch();

        $obj = new self();
        $obj->id = $data['id'];
        $obj->contactName = $contactName;
        $obj->contactPhone = $contactPhone;
        $obj->comment = $comment;
        $obj->address = $address;
        $obj->userId = $userId;
        return $obj;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getContactName(): string
    {
        return $this->contactName;
    }

    public function getContactPhone(): int
    {
        return $this->contactPhone;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getOrderProducts(): array
    {
        return $this->OrderProducts;
    }

}

