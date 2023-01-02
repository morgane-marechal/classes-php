<?php 
echo "Cette page fonctionne. <br>";
$mysqli = mysqli_connect('localhost','root','','classes');

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}else{
    echo "Vous êtes connecté à la base de donnée. <br>";
}

class User{
    private $id;
    public $login, $email, $firstname, $lastname;

    /*function __construct($login,  $email, $firstname, $lastname) {
        $this->login = $login;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }*/

    function register($login, $password, $email, $firstname,$lastname){
        global $mysqli;
        $this->password=$password;
        $this->login = $login;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;

        $newuser = "INSERT INTO utilisateurs ( login, password, email, firstname, lastname)
        VALUES( '$login','$password', '$email', '$firstname', '$lastname')";
        if ($mysqli->query($newuser) === TRUE) {
            echo "Vous avez ajouté un utilisateur avec succés.<br>";
            }else {
                echo "Erreur: " . $newuser . "
                " . $mysqli->error;
                }
    }

    function connect($login, $password){
        global $mysqli;
        $request="SELECT * FROM utilisateurs where login = '$login'and password= '$password'";
        
        if ($mysqli->query($request) === TRUE){
            echo "Bravo vous êtes connectés!";
        }else{
            echo "Problème d'identifiant ou de mot de passe";
        }
       

    }

  
    
}


$lucia= new User("Lucia","luci@gmail.com", "Lucie", "Alamond");
//$newuser->register("Lucia","azety","luci@gmail.com", "Lucie", "Alamond");
echo var_dump($lucia);
$lucia->register("Lucia","azerty","luci@gmail.com", "Lucie", "Alamond");

$kevin= new User("Kevin","kev@gmail.com", "Kevin", "Bond");
echo var_dump($kevin);
$kevin->register("Kevin","azerty2","kev@gmail.com", "Kevin", "Bond");

//$kevin->connect("Kevin", "azerty2");
?>