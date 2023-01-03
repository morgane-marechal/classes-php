<?php
session_start();
?>
<?php 
echo "Cette page fonctionne. <br>";
$mysqli = mysqli_connect('localhost','root','','classes');
echo "Login de session: ".$_SESSION['login']."<br>";

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

    public function connect($login, $password){
        global $mysqli;
        $this->password=$password;
        $this->login = $login;
        $request="SELECT count(*) FROM utilisateurs where login = '$login' and password = '$password'";
        $exec_requete = mysqli_query($mysqli,$request);
        $reponse = mysqli_fetch_array($exec_requete);
        $count = $reponse['count(*)'];
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

    public function disconnect(){
        if (isset($_SESSION['login'])&& !empty($_SESSION['login'])){
            echo "Vous êtes déconnecté";
            return session_destroy();

        }
    }

    public function isConnected(){
        // global $isConnected;
        // $isConnected=TRUE;
        if (isset($_SESSION['login'])){
            echo "Quelqu'un est connecté à cette page. Il s'agit de ".$_SESSION['login']."<br>";
            global $isConnected;
            return $isConnected = TRUE;
        }
    }

    public function delete(){
        global $mysqli;
        $delete="DELETE from utilisateurs WHERE login = '$this->login'";
        mysqli_query($mysqli,$delete);
        echo $this->login." supprimé";
        session_destroy();
    }

    public function update($newlogin, $password, $email, $firstname,$lastname){
        global $mysqli;
        $login=$_SESSION['login'];
        $this->password=$password;
        $this->login = $newlogin;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $sqlupdate = "UPDATE utilisateurs SET login = '$newlogin', password = '$password', email = '$email', firstname = '$firstname', lastname = '$lastname' WHERE login = '$login'";
        mysqli_query($mysqli,$sqlupdate);
        if ($mysqli->query($sqlupdate) === TRUE) {
            echo "Vous avez amise à jour votre profil.<br>";
            }else {
            echo "Erreur: " . $sqlupdate . "
            " . $mysqli->error;
            }
    }
  
    
}


$update= new User("Lucia","luci@gmail.com", "Lucie", "Alamond");
//$newuser->register("Lucia","azety","luci@gmail.com", "Lucie", "Alamond");
//echo var_dump($lucia);
//$lucia->register("Lucia","azerty","luci@gmail.com", "Lucie", "Alamond");
//$kevin->register("Kevin","azerty2","kev@gmail.com", "Kevin", "Bond");
//$barnabe= new User("Barnabe","nanrabar@gmail.com", "Bar Na Bay", "Glouglou");
//$yusu->register("Yusuka","123","yusss@gmail.com", "Yusune", "Mariko");
//echo var_dump($barnabe);
$update->connect("Lucianna","123CF");
//$update->update("Lucianna", "123CF", "lucia@gmail.com","Lucianna","Alamonde");

//echo $barnabe->isConnected();
//$barnabe->disconnect();
// if ($isConnected===TRUE){
//     echo "<br>La variable isConnected en global fonctionne, elle renvoie $isConnected";
// }
// $deleteUser= new User("Kevin","kev@gmail.com", "Kevin", "Bond");

//$deleteUser->delete();
?>