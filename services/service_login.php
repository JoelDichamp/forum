<?php
$connected = false;
if( isset( $_POST["pseudo"] ) && isset( $_POST["password"] ) ){

    $pseudo = $_POST["pseudo"];
    $password = sha1( $_POST["password"] . SALT );

    $login = getLogin( $pseudo, $password );
    if( $login ){ // Teste si utilisateur trouvé avec la requete (sinon null)

        $_SESSION["login"] = $login;
        $connected = true;
    }
}

if( $connected ){
    header("Location: ?page=home");
}
else {
    session_unset(); // Detruit toutes les variables de SESSION
    
    $error = urlencode("Identifiant ou mot de passe incorrect");
    header("Location: ?page=login&error=" . $error);
}