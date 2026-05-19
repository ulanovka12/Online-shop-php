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

    public function getByEmail(string $email): self|null
    {

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
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


        return $obj;
    }

    public function getByUsername(string $name, string $email, string $password): self|null
    {

        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);

        $result = $stmt->fetch();

        if ($result === false) {
            return null;
        }

        $obj = new self();

        $obj->id = $result['id'];
        $obj->name = $result['name'];
        $obj->email = $result['email'];
        $obj->password = $result['password'];

        return $obj;
    }

//    public function getByEmail(string $email ): self|null
//    {
//        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
//        $stmt->execute(['email' => $email]);
//        $user = $stmt->fetch();
//
//        if ($user === false){
//            return null;
//        }
//
//        $obj = new self();
//
//        $obj->id = $user['id'];
//        $obj->name = $user['name'];
//        $obj->email = $user['email'];
//        $obj->password = $user['password'];
//
//
//        return $obj;
//    }
//    public function getByEmailLogin(string $username) : self|null
//    {
//
//        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
//        $stmt->execute(['email' => $username]);
//        $user = $stmt->fetch();
//
//        if ($user === false){
//            return null;
//        }
//
//        $obj = new self();
//
//        $obj->id = $user['id'];
//        $obj->name = $user['name'];
//        $obj->email = $user['email'];
//        $obj->password = $user['password'];
//
//
//        return $obj;
//    }
//    public function ValidateCountRegistrate(string $email): self|null
//    {
//        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
//        $stmt->execute(['email' => $email]);
//        $user = $stmt->fetch();
//
//        if ($user === false) {
//            return null;
//        }
//
//        $obj = new self();
//
//        $obj->id = $user['id'];
//        $obj->name = $user['name'];
//        $obj->email = $user['email'];
//        $obj->password = $user['password'];
//
//        return $obj;
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

        $obj->id = $user['id'];
        $obj->name = $user['name'];
        $obj->email = $user['email'];
        $obj->password = $user['password'];


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
        return $this->psw;
    }
    public function getImage(): string
    {
        return $this->image_url;
    }


}