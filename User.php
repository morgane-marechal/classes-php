<?php
session_start();
?>
<?php 
echo "Cette page fonctionne. <br>";
$mysqli = mysqli_connect('localhost','root','','classes');
echo $_SESSION['login']."<br>";

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}else{
    echo "Vous êtes connecté à la base de donnée. <br>";
}

echo "<br><br><br>";


class User{
    private $id;
    public $login, $email, $firstname, $lastname;

    function __construct($login,  $email, $firstname, $lastname) {
        $this->login = $login;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }

    public function register($login, $password, $email, $firstname,$lastname){
        global $mysqli;
        $this->password=$password;
        $this->login = $login;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $check_login = "SELECT count(*) FROM utilisateurs where login = '$login'";
        $exec_requete = mysqli_query($mysqli,$check_login);
        $reponse = mysqli_fetch_array($exec_requete);
        $count = $reponse['count(*)'];
        if ($count!=0){
            echo "<p>Ce login est déjà pris, veuillez en choisir un autre!</p>";}
            else{
                $newPeople = "INSERT INTO utilisateurs ( login, password, email, firstname, lastname)
                VALUES( '$login','$password', '$email', '$firstname', '$lastname')";
                    if ($mysqli->query($newPeople) === TRUE) {
                    echo "Vous avez ajouté un utilisateur avec succés.<br>";
                    }else {
                    echo "Erreur: " . $newPeople . "
                    " . $mysqli->error;
                    }
            }
        
    }

    function connect($login, $password){
        echo "hello";
        global $mysqli;
        $this->password=$password;
        $this->login = $login;
        $request="SELECT count(*) FROM utilisateurs where login = '$login' and password = '$password'";
        $exec_requete = mysqli_query($mysqli,$request);
        $reponse = mysqli_fetch_array($exec_requete);
        //echo var_dump($reponse);
        $count = $reponse['count(*)'];
        if($count!=0){
            echo "Bravo vous êtes connectés!<br>";
            $_SESSION['login'] = $login;
            $_SESSION['email'] = $this->email;
            $_SESSION['firstname'] = $this->firstname;
            $_SESSION['lastname'] = $this->lastname;
        }else{
            echo "Problème d'identifiant ou de mot de passe";
        }
    }

    function disconnect(){
        if (isset($_SESSION['login'])&& !empty($_SESSION['login'])){
            echo "Vous êtes déconnecté";
            return session_destroy();

        }
    }

    function isConnected(){
        // global $isConnected;
        // $isConnected=TRUE;
        if (isset($_SESSION['login'])){
            echo "Quelqu'un est connecté à cette page. Il s'agit de ".$_SESSION['login']."<br>";
            global $isConnected;
            return $isConnected = TRUE;
        }
    }
  
    
}


$lucia= new User("Lucia","luci@gmail.com", "Lucie", "Alamond");
//$newuser->register("Lucia","azety","luci@gmail.com", "Lucie", "Alamond");
//echo var_dump($lucia);
//$lucia->register("Lucia","azerty","luci@gmail.com", "Lucie", "Alamond");
//$kevin->register("Kevin","azerty2","kev@gmail.com", "Kevin", "Bond");
$barnabe= new User("Barnabe","nanrabar@gmail.com", "Bar Na Bay", "Glouglou");
$yusu= new User("Yusuka","yusss@gmail.com", "Yusune", "Mariko");
//$yusu->register("Yusuka","123","yusss@gmail.com", "Yusune", "Mariko");
//echo var_dump($barnabe);
//$yusu->connect("Yusuka", "123");

//echo $barnabe->isConnected();
$barnabe->disconnect();

?>