<?php
interface CrudInterface {
    public function create($data);
    public function read();
    public function update($id, $data);
    public function delete($id);
}

class Konekcija {
    protected $conn;
    private $servername = "localhost";
    private $username = "root";
    private $password = ""; 
    private $database = "pracenje_ucenja";

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host={$this->servername};dbname={$this->database};charset=utf8", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Greška prilikom povezivanja sa bazom podataka: " . $e->getMessage());
        }
    }
}

class Korisnik extends Konekcija {
    
    public function registracija($ime, $prezime, $email, $lozinka) {
        $hashed_password = password_hash($lozinka, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO korisnici (ime, prezime, email, lozinka) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$ime, $prezime, $email, $hashed_password]);
    }

    public function login($email, $lozinka) {
        $stmt = $this->conn->prepare("SELECT * FROM korisnici WHERE email = ?");
        $stmt->execute([$email]);
        $korisnik = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($korisnik && password_verify($lozinka, $korisnik['lozinka'])) {
            $_SESSION['korisnik_id'] = $korisnik['id'];
            $_SESSION['ime_prezime'] = $korisnik['ime'] . " " . $korisnik['prezime'];
            return true;
        }
        return false;
    }
}

class NapredakUcenja extends Konekcija implements CrudInterface {
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO napredak (ucenik, predmet, zadatak, ocena, vreme) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$data['ucenik'], $data['predmet'], $data['zadatak'], $data['ocena'], $data['vreme']]);
    }

    public function read() {
        $stmt = $this->conn->prepare("SELECT * FROM napredak ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readOne($id) {
        $stmt = $this->conn->prepare("SELECT * FROM napredak WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE napredak SET ucenik=?, predmet=?, zadatak=?, ocena=?, vreme=? WHERE id=?");
        return $stmt->execute([$data['ucenik'], $data['predmet'], $data['zadatak'], $data['ocena'], $data['vreme'], $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM napredak WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>