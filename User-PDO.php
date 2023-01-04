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

   

}

$test = new User("Ala Dine","123XX", "aldino@gmail.com","Aladdin","Pshitt");
//echo $test -> register("Ala Dine","123XX", "aldino@gmail.com","Aladdin","Pshitt");
echo $test -> connect("Ala Dine", "123XX");
?>