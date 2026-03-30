<?php



namespace Model;

class User extends Model
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;

    public function getByEmail(string $email): array|false
    {

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);

        $result = $stmt->fetch();

        return $result;
    }

    public function getByUsername(string $name, string $email, string $password): array|false
    {

        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);

        $result = $stmt->fetch();

        return $result;
    }

    public function getByEmailLogin(string $username) : self|null
    {

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $username]);
        $user = $stmt->fetch();

        if ($user === false){
            return null;
        }

        $obj = new self();
        $obj->id = $user['id'];
        $obj->name = $user['name'];
        $obj->email = $user['email'];
        $obj->password = $user['password'];

        return $obj;
    }
    public function ValidateCountRegistrate(string $email): self|null
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        // Если пользователь не найден, возвращаем null
        if ($user === false) {
            return null;
        }

        // Для отладки: посмотрим что возвращает запрос
        // var_dump($user); exit; // Раскомментируйте для проверки

        // Создаем объект пользователя
        $obj = new self();

        // Проверяем наличие ключей перед присвоением
        $obj->id = isset($user['id']) ? (int)$user['id'] : 0;
        $obj->name = isset($user['name']) ? (string)$user['name'] : '';
        $obj->email = isset($user['email']) ? (string)$user['email'] : '';
        $obj->password = isset($user['password']) ? (string)$user['password'] : '';

        return $obj;
    }
//    public function ValidateCountRegistrate(string $email):self|null
//    {
//
//        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
//        $stmt->execute(['email' => $email]);
//        $user = $stmt->fetch();
//
//        if ($user === false)
//        {
//            return null;
//        }
//
//        $obj = new self();
//        $obj->id = (int)$user['id'];
//        $obj->name = (string)$user['name'];
//        $obj->email = (string)$user['email'];
//        $obj->password = (string)$user['password'];
//
//        return $obj;
//
//    }
    public function getByIdProfile(int $userId): self|null
    {

        $stmt = $this->pdo->query("SELECT * FROM users WHERE id = $userId");
        $user = $stmt->fetch();

        if ($user === false)
        {
            return null;
        }

        $obj = new self();
        $obj->id = (int)$user['id'];
        $obj->name = (string)$user['name'];
        $obj->email = (string)$user['email'];
        $obj->password = (string)$user['password'];

        return $obj;
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
        return $this->password;
    }


}