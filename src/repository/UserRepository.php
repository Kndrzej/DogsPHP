<?php

require_once 'Repository.php';
require_once __DIR__.'/../models/User.php';

class UserRepository extends Repository
{

    public function getUser(string $email): ?User
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM users u LEFT JOIN users_details ud 
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
            $user['id']
        );
    }

    public function checkIsAdminByEmail(string $email): bool
    {
        $user = $this->getUser($email);

        return $user->isAdmin();
    }

    public function addUser(User $user)
    {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO users_details (name, surname, phone)
            VALUES (?, ?, ?, ?)
        ');

        $stmt->execute([
            $user->getName(),
            $user->getSurname(),
            $user->getPhone()
        ]);

        $stmt = $this->database->connect()->prepare('
            INSERT INTO users (email, password, id_user_details, admin)
            VALUES (?, ?, ?, ?)
        ');

        $stmt->execute([
            $user->getEmail(),
            $user->getPassword(),
            $this->getUserDetailsId($user),
            $user->isAdmin(),
        ]);
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
                password = :password
                admin = :admin
             WHERE email = :email'
        );
        $email = $user->getEmail();
        $password = $user->getPassword();
        $admin = $user->isAdmin();
        $data = [
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'admin' => $admin,
            'email' => $email,
        ];

        $return = $stmt->execute($data);
        if ($details) {
            $this->updateUserDetails($user);
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
}