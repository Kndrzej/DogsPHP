<?php

require_once 'Repository.php';
require_once __DIR__.'/../models/User.php';

class UserRepository extends Repository
{

    public function getUser(string $email): ?User
    {
        $stmt = $this->database->connect()->prepare('
            SELECT u.id AS id_user, * FROM users u LEFT JOIN users_details ud 
            ON u.id_user_details = ud.id WHERE email = :email
        ');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user == false) {
            return null;
        }

        return new User(
            $user['email'],
            $user['password'],
            $user['name'],
            $user['surname'],
            $user['admin'],
            $user['id_user']
        );
    }

    public function getUserIdByEmail(string $email): int
    {
        $user = $this->getUser($email);

        return $user->getId();
    }

    public function addUser(User $user)
    {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO users_details (name, surname, phone)
            VALUES (?, ?, ?)
        ');

        $stmt->execute([
            $user->getName(),
            $user->getSurname(),
            $user->getPhone()
        ]);

        $stmt = $this->database->connect()->prepare('
            INSERT INTO users (email, password, id_user_details, admin)
            VALUES (:email, :password, :id_user_details, :admin)
        ');
        $email = $user->getEmail();
        $password = $user->getPassword();
        $id_user_details = $this->getUserDetailsId($user);
        $admin = $user->isAdmin();
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':id_user_details', $id_user_details, PDO::PARAM_INT);
        $stmt->bindParam(':admin', $admin, PDO::PARAM_BOOL);

        $stmt->execute();
    }

    public function getUserDetailsId(User $user): int
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM public.users_details WHERE name = :name AND surname = :surname AND phone = :phone
        ');
        $name = $user->getName();
        $surname = $user->getSurname();
        $phone = $user->getPhone();
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data['id'];
    }

    public function updateUser(User $user, bool $details = false): bool
    {
        $stmt = $this->database->connect()->prepare(
            'UPDATE users SET
                password = :password,
                admin = :admin
             WHERE email = :email'
        );
        $email = $user->getEmail();
        $password = $user->getPassword();
        $admin = $user->isAdmin();
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':admin', $admin, PDO::PARAM_BOOL);
        $stmt->bindParam(':email', $email);

        $return = $stmt->execute();
        if ($details) {
            $return &= $this->updateUserDetails($user);
        }

        return $return;
    }

    private function updateUserDetails(User $user): bool
    {
        $stmt = $this->database->connect()->prepare(
            'UPDATE public.users_details SET
                    name = :name,
                    surname = :surname,
                    phone = :phone
                WHERE  email = :email'
        );
        $name = $user->getName();
        $surname = $user->getSurname();
        $phone = $user->getPhone();
        $email = $user->getEmail();
        $data = [
            'name' => $name,
            'surname' => $surname,
            'phone' => $phone,
            'email' => $email
        ];

        return $stmt->execute($data);
    }

    public function createUsersView()
    {
        $this->database->connect()->exec(
            'CREATE OR REPLACE VIEW vw_users AS
            SELECT
                u.id AS id_user,
                u.email,
                ud.name,
                ud.surname,
                ud.phone,
                CASE
                    WHEN u.admin = TRUE THEN \'YES\'
                    ELSE \'NO\'
                END AS is_admin
            FROM users u
            LEFT JOIN users_details ud ON u.id_user_details = ud.id'
        );
    }

    public function selectUsersView()
    {
        $stmt = $this->database->connect()->query('SELECT * FROM vw_users');
        $stmt->execute();

        return $stmt->fetchAll();
    }
}