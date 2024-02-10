<?php

include './lib/Database.php';
include_once './lib/Session.php';

class UserController
{

    //  Property
    private $db;
    private $validate;

    //  __construct Method
    public function __construct()
    {
        $this->db = new Database();
        $this->validate = new Validator($this->db);
    }

    // Select All User Method
    public function index()
    {

        try {
            $itemsPerPage = 10;
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $itemsPerPage;

            $sql = "SELECT * FROM users ORDER BY id DESC LIMIT $offset, $itemsPerPage";
            $stmt = $this->db->pdo->prepare($sql);
            $stmt->execute();

            // Count total number of rows
            $totalRows = $stmt->rowCount();
            // Calculate total number of pages
            $totalPages = ceil($totalRows / $itemsPerPage);

            return [
                'message' => 'Data loaded',
                'data' => $stmt->fetchAll(PDO::FETCH_OBJ),
                'pagination' => [
                    'totalPages' => $totalPages,
                ],
            ];
        } catch (Throwable $e) {
            // Handle the error or exception
            return [
                'message' => $e->getMessage(),
            ];
        }
    }

    // User Registration Method
    public function store($data)
    {
        try {
            if (!$this->validate->isValidUser($data) || $this->validate->checkExistEmail($data['email'])) {
                return ['message' => $this->validate->getMessage(),
                    'old_data' => $data,
                ];
            }

            $data = $this->validate->getTrimmedData();

            $sql = "INSERT INTO users( username, email, password, role) VALUES(:username, :email, :password, :role)";
            $stmt = $this->db->pdo->prepare($sql);
            $stmt->bindValue(':username', $data['username']);
            $stmt->bindValue(':email', $data['email']);
            $stmt->bindValue(':password', $this->hashPassword($data['password']));
            $stmt->bindValue(':role', $data['role']);
            $result = $stmt->execute();

            if ($result) {
                return ['message' => $this->validate->getSuccessAlert("You have successfully added a new user")];
            } else {
                return ['message' => $this->validate->getErrorAlert()];
            }
        } catch (Throwable $e) {
            // Handle the error or exception
            return [
                'message' => $e->getMessage(),
            ];
        }
    }

    //   Get Single User Information By Id Method
    public function update($userid, $data)
    {

        try {
            if (!$this->validate->isValidUser($data) || $this->validate->checkExistEmailToOthers($data['email'], $userid)) {
                return ['message' => $this->validate->getMessage(),
                    'old_data' => $data,
                ];
            }
            $data = $this->validate->getTrimmedData();

            $new_password = $data['new_password'];
            $password = $data['password'];

            $sql = "UPDATE users SET username = :username,password= :password, email = :email, role = :role WHERE id = :id";
            $stmt = $this->db->pdo->prepare($sql);
            $stmt->bindValue(':username', $data['username']);
            $stmt->bindValue(':email', $data['email']);
            $stmt->bindValue(':password', $this->hashPassword($new_password === '' ? $password : $new_password));
            $stmt->bindValue(':role', $data['role']);
            $stmt->bindValue(':id', $userid);
            $result = $stmt->execute();

            if ($result) {
                echo "<script>location.href='index.php';</script>";
                Session::set('msg', $this->validate->getSuccessAlert("You have successfully updated user information"));

            } else {
                echo "<script>location.href='index.php';</script>";
                Session::set('msg', $this->validate->getErrorAlert());

            }
        } catch (Throwable $e) {
            // Handle the error or exception
            return [
                'message' => $e->getMessage(),
            ];
        }

    }

    // search user by email and password
    public function search($keyword)
    {

        try {
            $itemsPerPage = 10;
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $itemsPerPage;
            $stmt = $this->db->pdo->prepare("SELECT id, username, email, role FROM users WHERE username LIKE ? OR email LIKE ? LIMIT $offset, $itemsPerPage");
            $stmt->execute(["%$keyword%", "%$keyword%"]);

            // Count total number of rows
            $totalRows = $stmt->rowCount();
            // Calculate total number of pages
            $totalPages = ceil($totalRows / $itemsPerPage);

            return [
                'message' => 'Data loaded',
                'data' => $stmt->fetchAll(PDO::FETCH_OBJ),
                'pagination' => [
                    'totalPages' => $totalPages,
                ],
            ];
        } catch (Throwable $e) {
            // Handle the error or exception
            return [
                'message' => $e->getMessage(),
            ];
        }
    }

    // Get Single User Information By Id
    public function get($userid)
    {

        try {
            $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
            $stmt = $this->db->pdo->prepare($sql);
            $stmt->bindValue(':id', $userid);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            if ($result) {
                return $result;
            } else {
                return false;
            }
        } catch (Throwable $e) {
            // Handle the error or exception
            return [
                'message' => $e->getMessage(),
            ];
        }
    }

    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // Delete User by Id Method
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM users WHERE id = :id ";
            $stmt = $this->db->pdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $result = $stmt->execute();
            if ($result) {
                return $this->validate->getSuccessAlert('Successfully deleted user!');
            } else {
                return $this->validate->getErrorAlert();
            }
        } catch (Throwable $e) {
            // Handle the error or exception
            return [
                'message' => $e->getMessage(),
            ];
        }
    }

}
