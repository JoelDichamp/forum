<?php  
    function melOK( &$error, &$e_mail ) {

        $ok = false;
        if (empty($_POST["e_mail"])) {
            $error = "L'adresse e-mail n'a pas été saisie !";
            $_SESSION["fields"]["e_mail"] = "";
        } else {
            $e_mail = htmlspecialchars($_POST["e_mail"]);
            //"#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#"
            if ( preg_match("#^[a-z0-9_-]+[.]?[a-z0-9_-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $e_mail) ) { 
                $ok = true;
            } else {
                $error = "L'adresse e-mail est invalide !";
            }
        }

        return $ok;
    }

    function pseudoOK( &$error, &$pseudo ) {
        $ok = false;
        if ( empty($_POST["pseudo"]) ) {
            $error = "Le pseudo n'a pas été saisi !";
        } else {
            $pseudo = trim($_POST["pseudo"]);
            if ( strlen( $pseudo ) < LG_PSEUDO ) {
                $error = "Le pseudo doit comporter au moins " . LG_PSEUDO . " caractères !";
            } else if ( strpos( $pseudo, " ") ) {
                $error = "Le pseudo ne doit pas contenir de caractères espace !";
            } else {
                $pseudo = htmlspecialchars( $pseudo );
                $ok = true;
            }  
        }
        if (!$ok) {
            $_SESSION["fields"]["pseudo"] = "";
        }
 
        return $ok;
    }

    function passwordOK( &$error, &$password ) {

        $ok = false;
        if (empty($_POST["password"])) {
            $error = "Le password n'a pas été saisi !";
        } else {
            $password = $_POST["password"];
            if ( strlen( $password ) < LG_PASSWORD ) {
                $error = "Le password doit comporter au moins ". LG_PASSWORD . " caractères !";
                $_SESSION["fields"]["password"] = "";
            } else if (empty($_POST["password_again"])) {
                $error = "Le password n'a pas été retapé !";
            } else {
                $password_again = $_POST["password_again"];
                if ($password != $password_again) {
                    $error = "Les mots de passe ne sont pas identiques !";
                    $_SESSION["fields"]["password"] = "";
                    $_SESSION["fields"]["password_again"] = "";
                } else {
                    $password = sha1( $_POST["password"] . SALT );
                    $ok = true;
                }  
            }
        }

        return $ok;
    }
    
    function pseudoExists( &$error, $pseudo ) {
        $exists = false;
        if (pseudoExistsAlready($pseudo)) {
            $error = "L'utilisateur avec le pseudo " . $pseudo . " existe déjà !";
            $_SESSION["fields"]["pseudo"] = "";
            $exists = true;
        }

        return $exists;
    }

    $error = '';
    $e_mail = '';
    $pseudo = '';
    $password = '';
    $_SESSION["fields"] = $_POST;

    if ( melOK( $error, $e_mail ) && 
         pseudoOK($error, $pseudo) && 
         passwordOK($error, $password) && 
         !pseudoExists($error, $pseudo) ) {

            $login = [
                "id" => -1,
                "id_role" => USER,
                "pseudo" => $pseudo,
                "password" => $password,
                "e_mail" => $e_mail
            ];
            if ( createLogin( $login, $login["id"] )) {
                unset( $_SESSION["fields"] );
                $_SESSION["login"] = $login;
                header("Location: ?page=home");
            } else {
                $error = "Une erreur est survenue lors de la création de l'utilisateur.";
            } 
    }
    
    if ($error != '') {
        $error = urlencode($error);
        header("Location: ?page=create_login&error=" . $error);
    }   
?>