<?php

include './lib/Database.php';
include_once './lib/Session.php';

class UserController
{

    //  Property
    private $db;
    private $validate;
    private $itemsPerPage = 5;

    //  __construct Method    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->db = new Database();
        $this->validate = new Validator($this->db);
    }

    // Select All User Method    

    /**
     * index
     *
     * @return array
     */
    public function index(): array
    {

        try {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $this->itemsPerPage;

            $sql = "SELECT * FROM users ORDER BY id DESC LIMIT $offset, $this->itemsPerPage";
            $stmt = $this->db->pdo->prepare($sql);
            $stmt->execute();

            return [
                'message' => 'Data loaded',
                'data' => $stmt->fetchAll(PDO::FETCH_OBJ),
                'pagination' =>
                $this->paginate($page),
            ];
        } catch (Throwable $e) {
            // Handle the error or exception
            return [
                'message' =>
                $this->validate->getErrorAlert(),
            ];
        }
    }

    // User Registration Method    
    /**
     * store
     *
     * @param  mixed $data
     * @return void
     */
    public function store($data): array
    {
        try {
            if (!$this->validate->isValidUser($data) || $this->validate->checkExistEmail($data['email'])) {
                return [
                    'message' => $this->validate->getMessage(),
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
                'message' =>
                $this->validate->getErrorAlert(),
            ];
        }
    }

    //   Get Single User Information By Id Method    
    /**
     * update
     *
     * @param  int $userid
     * @param  mixed $data
     * @return mixed
     */
    public function update($userid, $data): mixed
    {

        try {
            if (!$this->validate->isValidUser($data) || $this->validate->checkExistEmailToOthers($data['email'], $userid)) {
                return [
                    'message' => $this->validate->getMessage(),
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
                'message' =>
                $this->validate->getErrorAlert(),
            ];
        }
    }

    // search user by email and password    
    /**
     * search
     *
     * @param  string $keyword
     * @return void
     */
    public function search($keyword): array
    {

        try {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $this->itemsPerPage;
            $stmt = $this->db->pdo->prepare("SELECT id, username, email, role FROM users WHERE username LIKE ? OR email LIKE ? LIMIT $offset, $this->itemsPerPage");
            $stmt->execute(["%$keyword%", "%$keyword%"]);

            return [
                'message' => 'Data loaded',
                'data' => $stmt->fetchAll(PDO::FETCH_OBJ),
                'pagination' => $this->paginate($page),
            ];
        } catch (Throwable $e) {
            // Handle the error or exception
            return [
                'message' =>
                $this->validate->getErrorAlert(),
            ];
        }
    }

    // Get Single User Information By Id    
    /**
     * get
     *
     * @param  mixed $userid
     */
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
                'message' =>
                $this->validate->getErrorAlert(),
            ];
        }
    }

    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // Delete User by Id Method    
    /**
     * delete
     *
     * @param  mixed $id
     * @return array
     */
    public function delete($id): array
    {
        try {
            $sql = "DELETE FROM users WHERE id = :id ";
            $stmt = $this->db->pdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $result = $stmt->execute();
            if ($result) {
                return ['message' => $this->validate->getSuccessAlert('Successfully deleted user!')];
            } else {
                return ['message' => $this->validate->getErrorAlert()];
            }
        } catch (Throwable $e) {
            // Handle the error or exception
            return [
                'message' =>
                $this->validate->getErrorAlert(),
            ];
        }
    }

    public function paginate($page)
    {
        // Query to count total number of rows
        $sqlCount = "SELECT COUNT(*) AS total FROM users";
        $resultCount = $this->db->pdo->prepare($sqlCount);
        $resultCount->execute();
        $rowCount = $resultCount->fetch();
        $totalRows = $rowCount['total'];

        // Calculate total number of pages
        $totalPages = ceil($totalRows / $this->itemsPerPage);

        return [
            'totalPages' => $totalPages,
            'page' => $page
        ];
    }
}
