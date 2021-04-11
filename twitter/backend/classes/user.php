<?php 

class User {
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::instance();
    }

    public function userData($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = :userId");
        $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
        $stmt->execute();
        // echo "<pre>";
        // $stmt->debugDumpParams();
        // echo "</pre>";
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        if($stmt->rowCount() != 0) {
            return $user;
        } else {
            return false;
        }
    }
}

?>