<?php

// --------------------connexion à PDO----------------------------

$dsn = 'mysql:host=localhost;dbname=classes;charset=utf8';
$user = 'root';
$password = '';
$bdd = new PDO($dsn,$user,$password);

try{
    $bdd=new PDO('mysql:host=localhost;dbname=classes;charset=utf8','root','');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "La connection avec PDO fonctionne";
}catch(PDOException $e){
    echo "Echec de la connexion: ".$e->getmessage();
    exit;
}


// --------------------Définition de la classe User----------------------------

class User{
    private $id;
    public $login, $password, $email, $firstname, $lastname;

    function __construct($login, $password, $email, $firstname, $lastname) {
        $this->login = $login;
        $this->password = $password;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }

    public function register(){
        global $bdd;
        
        $check_login = $bdd ->prepare("SELECT count(*) as count FROM utilisateurs where login = '$this->login'");
        $check_login->execute();
        $res = $check_login->fetch(PDO::FETCH_ASSOC);
        //echo var_dump($res);
        $count = intval($res['count']);
        if ($count>0){
        echo "<p>Ce login est déjà pris, veuillez en choisir un autre!</p>";}
        else{
                $newPeople = $bdd->prepare("INSERT INTO utilisateurs ( login, password, email, firstname, lastname)
                 VALUES(?,?,?,?,? )");
               $newPeople->execute(array($this->login,$this->password, $this->email,$this->firstname,$this->lastname));
               echo "Vous avez ajouté un nouvel utilisateur avec succès";
        }
    }

    public function connect($login, $password){
        global $bdd;
        $this->password=$password;
        $this->login = $login;
        $check_login = $bdd ->prepare("SELECT count(*) as count FROM utilisateurs where login = '$this->login'");
        $check_login->execute();
        $res = $check_login->fetch(PDO::FETCH_ASSOC);
        //echo var_dump($res);
        $count = intval($res['count']);
        if($count!=0){
            echo "Bravo vous êtes connectés!
            <br> Si ce message s'affiche c'est que vous vous êtes connecté avec succès<br>";
            $_SESSION['login'] = $login;
            $_SESSION['email'] = $this->email;
            $_SESSION['firstname'] = $this->firstname;
            $_SESSION['lastname'] = $this->lastname;
            echo "Voici vos identifiants de session: ".$login.", ".$this->email.", ".$this->firstname.", ".$this->lastname."<br>";
        }else{
            echo "Problème d'identifiant ou de mot de passe";
        }
    }

    public function delete(){
        global $bdd;
        $delete= $bdd ->prepare("DELETE from utilisateurs WHERE login = '$this->login'");
        $delete->execute();
        echo "<br>".$this->login." supprimé";
        session_destroy();
    }

    public function update($newlogin, $password, $email, $firstname,$lastname){
        global $bdd;
        $login=$_SESSION['login']; //<- la fonction update ne fonctionne qui si un utilisateur est connecté
        $this->password=$password;
        $this->login = $newlogin;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $sqlupdate = $bdd -> prepare("UPDATE utilisateurs SET login = '$newlogin', password = '$password', email = '$email', firstname = '$firstname', lastname = '$lastname' WHERE login = '$login'");
        $sqlupdate->execute(array($this->login,$this->password, $this->email,$this->firstname,$this->lastname));
        echo "Vous avez mis à jout votre profil.<br>";
    }

    public function isConnected(){
        if (isset($_SESSION['login'])){
            echo "Quelqu'un est connecté à cette page. Il s'agit de ".$_SESSION['login']."<br>";
            global $isConnected;
            return $isConnected = TRUE;
        }
    }

    public function getAllInfos(){
        global $bdd;
        $allInfo = $bdd -> prepare("SELECT * FROM utilisateurs WHERE login = '$this->login'");
        $allInfo -> execute();
        $result = $allInfo->fetch(PDO::FETCH_ASSOC);
        echo var_dump($result);
        //tableau html -->
        echo "<table>
        <thead><tr><td>Id</td><td>Login</td><td>MP</td><td>Email</td><td>Prénom</td><td>Nom</td></tr></thead>
        <tbody><tr><td> ".$result['id']." </td><td>".$result['login']."</td><td>".$result['password']."</td><td>".$result['email']."</td><td>".$result['firstname']."</td><td>".$result['lastname']."</td></tr></tbody>
        </table>";
    }

    public function getLogin(){
        global $bdd;
        $allInfo = $bdd -> prepare("SELECT * FROM utilisateurs WHERE login = '$this->login'");
        $allInfo -> execute();
        $result = $allInfo->fetch(PDO::FETCH_ASSOC);
        echo "<br>Le login est ".$result ['login'];
    }

    public function getEmail(){
        global $bdd;
        $allInfo = $bdd -> prepare("SELECT * FROM utilisateurs WHERE login = '$this->login'");
        $allInfo -> execute();
        $result = $allInfo->fetch(PDO::FETCH_ASSOC);
        echo "<br>L'email est ".$result ['email'];
    }

    public function getFirstname(){
        global $bdd;
        $allInfo = $bdd -> prepare("SELECT * FROM utilisateurs WHERE login = '$this->login'");
        $allInfo -> execute();
        $result = $allInfo->fetch(PDO::FETCH_ASSOC);
        echo "<br>Le prénom  est ".$result ['firstname'];
    }

    public function getLastname(){
        global $bdd;
        $allInfo = $bdd -> prepare("SELECT * FROM utilisateurs WHERE login = '$this->login'");
        $allInfo -> execute();
        $result = $allInfo->fetch(PDO::FETCH_ASSOC);
        echo "<br>Le nom de famille est ".$result ['lastname'];
    }
}

$test = new User("Aladdin Gredin","supermotdepasse", "aldino@gmail.com","Aladdin","Pshitt");
$test -> getAllInfos();
//echo $test -> register("Ala Dine","123XX", "aldino@gmail.com","Aladdin","Pshitt");
//echo $test -> connect("Aladdin Gredin", "supermotdepasse");
//echo $test -> update("Aladdin Gredin","supermotdepasse", "aldino@gmail.com","Aladdin","Pshitt")
?>