<?php



namespace Model;

class User extends Model
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;
    private ?string $psw = null;
    private string $image_url = '';

    protected function getTableName(): string
    {
        return "users";
    }

    public function getByEmail(string $email): self|null
    {

        $stmt = $this->pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE email = :email");
        $stmt->execute(['email' => $email]);

        $result = $stmt->fetch();

        if ($result === false) {
            return null;
        }

        $obj = new self();

        $obj->id = $result['id'];
        $obj->name = $result['name'];
        $obj->email = $result['email'];
        $obj->password = $result['password'];
        $obj->image_url = $result['image_url'] ?? '';

        return $obj;
    }

    public function getByUsername(string $name, string $email, string $password): self|null
    {

        $stmt = $this->pdo->prepare("INSERT INTO {$this->getTableName()} (name, email, password) VALUES (:name, :email, :password) RETURNING id, name, email, password, image_url");
        $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);

        $result = $stmt->fetch();

        if ($result === false) {
            return null;
        }

        $obj = new self();

        $obj->id = $result['id'];
        $obj->name = $result['name'];
        $obj->email =  $result['email'];
        $obj->password =  $result['password'];
        $obj->image_url = $result['image_url'] ?? '';

        return $obj;
    }

    public function getByIdProfile(int $userId): self|null
    {
        // БАГ 1 (безопасность): $userId подставлялся прямо в текст SQL-запроса ($this->pdo->query("... id = $userId")).
        // Если бы $userId пришёл не из проверенного места, а напрямую от пользователя (например, из cookie),
        // через него можно было бы сделать SQL-инъекцию. БЫЛО: query() со строкой -> СТАЛО: prepare()
        // с именованным параметром :id, значение которого PDO передаёт отдельно от текста запроса и не даёт
        // интерпретировать его как часть SQL. ПОЧЕМУ: prepare()+execute() — стандартный безопасный способ
        // передавать переменные в запрос, его и используют остальные методы этого класса.
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch();

        if ($user === false)
        {
            return null;
        }

        $obj = new self();

        $obj->id = $user['id'];
        $obj->name = $user['name'];
        $obj->email = $user['email'];
        $obj->password = $user['password'];
        // БАГ 2: было обращение к переменной $result, которая в этом методе вообще не существует
        // (нет такой переменной выше по коду) — это должно было вызывать Warning "Undefined variable $result"
        // и image_url у профиля всегда был бы пустым. БЫЛО: $result['image_url'] -> СТАЛО: $user['image_url'],
        // т.к. именно в $user лежат данные, полученные из БД строкой выше.
        $obj->image_url = $user['image_url'] ?? '';

        return $obj;
    }

    public function updateProfile(int $userId, string $name, string $email, ?string $passwordHash): void
    {
        if ($passwordHash !== null) {
            $stmt = $this->pdo->prepare(
                "UPDATE {$this->getTableName()} SET name = :name, email = :email, password = :password WHERE id = :id"
            );
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'password' => $passwordHash,
                'id' => $userId,
            ]);
            return;
        }

        $stmt = $this->pdo->prepare("UPDATE {$this->getTableName()} SET name = :name, email = :email WHERE id = :id");
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'id' => $userId,
        ]);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
    public function getPswPassword(): string
    {
        return $this->psw;
    }
    public function getImage(): string
    {
        return $this->image_url;
    }


}