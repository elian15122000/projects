<?php

    class Account {
        private $pdo;
        private $errorArray = array();

        public function __construct() {
            $this->pdo = Database::instance();
        }

        public function register($vn, $nn, $un, $em, $pw, $pw2) {
            $this->validatesVorname($vn);
            $this->validatesNachname($nn);
            $this->validateEmail($em);
            $this->validatePasswort($pw, $pw2);
            if(empty($this->errorArray)) {
                return $this->insertUserDetails($vn, $nn, $un, $em, $pw);
            } else {
                return false;
            }
        }

        private function insertUserDetails($vn, $nn, $un, $em, $pw) {
            $passwortHash = password_hash($pw, PASSWORD_BCRYPT);
            $random = rand(0, 1);
            if($random == 0) {
                $profilBild = "frontend/assets/images/profilbild.png";
                $profilBanner = "frontend/assets/images/profilbanner.jpg";
            } else if($random == 1) {
                $profilBild = "frontend/assets/images/profilbild2.jpg";
                $profilBanner = "frontend/assets/images/profilbanner2.jpg";
            }

            $stmt = $this->pdo->prepare("INSERT INTO users (vorname, nachname, username, email, passwort, profilbild, profilbanner) VALUES (:vn, :nn, :un, :em, :pw, :pbd, :pbn)");
            $stmt->bindParam(":vn", $vn, PDO::PARAM_STR);
            $stmt->bindParam(":nn", $nn, PDO::PARAM_STR);
            $stmt->bindParam(":un", $un, PDO::PARAM_STR);
            $stmt->bindParam(":em", $em, PDO::PARAM_STR);
            $stmt->bindParam(":pw", $passwortHash, PDO::PARAM_STR);
            $stmt->bindParam(":pbd", $profilBild, PDO::PARAM_STR);
            $stmt->bindParam(":pbn", $profilBanner, PDO::PARAM_STR);
        
            $stmt->execute();

            return $this->pdo->lastInsertId();

        }

        private function validatesVorname($vn) {
            // if(strlen($vn) < 2 || strlen($vn) > 25) {
            //     array_push($this->errorArray, Konstanten::$vornameZeichen);
            // }
            if($this->length($vn, 2, 25)) {
                array_push($this->errorArray, Konstanten::$vornameZeichen); 
                return;
            }
        }

        private function validatesNachname($nn) {
            // if(strlen($nn) < 2 || strlen($nn) > 25) {
            //     array_push($this->errorArray, Konstanten::$nachnameZeichen);
            // }

            if($this->length($nn, 2, 25)) {
                array_push($this->errorArray, Konstanten::$nachnameZeichen); 
                return;
            }
        }

        public function generateUsername($vn, $nn) {
            if(!empty($vn) && !empty($nn)) {
                if(!in_array(Konstanten::$vornameZeichen, $this->errorArray) && !in_array(Konstanten::$nachnameZeichen, $this->errorArray)) {
                    $username = strtolower($vn."".$nn);
                    if($this->checkUsernameExist($username)) {
                        $screenRand = rand();
                        $userLink = "".$username."".$screenRand;
                    } else {
                        $userLink = $username;
                    }
                    return $userLink;
                }
            }
        }


        private function validateEmail($em) {
            $stmt = $this->pdo->prepare("SELECT email FROM users WHERE email=:email");
            $stmt->bindParam(":email", $em, PDO::PARAM_STR);
            $stmt->execute();
            if($stmt->rowCount() != 0) {
                array_push($this->errorArray, Konstanten::$emailBesetzt);
            }

            if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
                array_push($this->errorArray, Konstanten::$emailInvalid);
                return;
            }
        }


        private function checkUsernameExist($username) {
            $stmt = $this->pdo->prepare("SELECT username FROM users WHERE username=:username");
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->rowCount();
            if($count > 0) {
                return true;
            } else {
                return false;
            }
        }

        private function validatePasswort($pw, $pw2) {
            if($pw != $pw2) {
                array_push($this->errorArray, Konstanten::$passwortInvalid);
                return;
            }
            if($this->length($pw, 5, 25)) {
                array_push($this->errorArray, Konstanten::$passwortKurz);
                return;
            }
            if($this->length($pw2, 5, 25)) {
                array_push($this->errorArray, Konstanten::$passwortKurz);
                return;
            }
            if(preg_match("/[^A-Za-z0-9]/", $pw)) {
                array_push($this->errorArray, Konstanten::$passwortFalsch);
                return;
            }
        }


        private function length($input, $min, $max) {
            if(strlen($input) < $min) {
                return true;
            } else if(strlen($input) > $max) {
                return true;
            }
        }

        public function getError($error) {
            if(in_array($error, $this->errorArray)) {
                return "<small class='error' style='color: red'>$error</small>";
            }
        }
    }
?>