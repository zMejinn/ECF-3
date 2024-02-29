<?php
namespace Daeme\SRC;


use PDO;
use PDOException;

require_once __DIR__ . '/../vendor/autoload.php';

class UserModel {
    private PDO $db; // Instance de PDO

    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }

    

    public function authenticate(string $usernameOrEmail, string $password): ?array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM user WHERE username = :username OR email = :email");
            $stmt->execute(['username' => $usernameOrEmail, 'email' => $usernameOrEmail]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
            return null;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function isAdmin(int $userId): bool {
        try {
            $stmt = $this->db->prepare("SELECT role FROM user WHERE id = :userId");
            $stmt->execute(['userId' => $userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user && $user['role'] === 'admin';
        } catch (PDOException $e) {
            throw $e;
        }
    }
}

class UserController {
    private UserModel $userModel;

    public function __construct(PDO $db) {
        $this->userModel = new UserModel($db);
    }

    public function login(string $usernameOrEmail, string $password): void {
        $user = $this->userModel->authenticate($usernameOrEmail, $password);
        if ($user) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user'] = $user;
            $redirectUrl = ($user['role'] === 'admin') ? '/admin' : '/';
            header("Location: $redirectUrl");
            exit;
        } else {
            header('Location: /login?error=invalid_credentials');
            exit;
        }
    }

    public function logout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: /login');
        exit;
    }
}
