<?php 
    function id_categoryOK( &$error, &$id_category ) {
        $ok = false;
        if ( empty($_POST["category"]) ) {
            $error = "La catégorie n'a pas été saisie !";
        } else {
            // Vérifier que $category existe dans la base
            $id_category = $_POST["category"];
            if ( !categoryExistsById($id_category) ) {
                $error = "La catégorie sélectionnée n'existe pas !";
            } else {
                $ok = true;
            }
        }

        if (!$ok) {
            $_SESSION["fields"]["category"] = "";
        }

        return $ok;
    }

    function topicOK( &$error, &$topic, $id_category ) {
        $ok = false;
        if ( empty($_POST["topic"]) ) {
            $error = "Le titre du sujet n'a pas été saisi !";
        } else {
            //taille limitée à LG_TOPIC 
            $topic = trim($_POST["topic"]);
            if ( strlen( $topic ) > LG_TOPIC ) {
                $error = "Le titre du sujet ne doit pas excéder " . LG_TOPIC . " caractères !";
            } else {
                //existe en DB ?
                if ( topicExists( $topic, $id_category ) ) {
                    $error = "Le sujet '" . $topic ."' saisi existe déjà pour cette catégorie !";
                } else {
                    $topic = htmlspecialchars($topic);
                    $ok = true;
                }
            }
        }

        if (!$ok) {
            $_SESSION["fields"]["topic"] = "";
        }

        return $ok;
    }

    function postOK( &$error, &$post ) {
        $ok = false;
        
        if ( checkPost( $error, $post ) ) {
            $ok = true;
        } else {
            $_SESSION["fields"]["post"] = "";
        }

        return $ok; 
    }

    $error = '';
    $id_category = '';
    $topic = '';
    $post = ''; 
    $_SESSION["fields"] = $_POST;
    
    if ( id_categoryOK( $error, $id_category ) && topicOK( $error, $topic, $id_category ) && postOK( $error, $post) ) {
        $last_id_topic = 0;
        if ( createTopic( $id_category, $topic, $last_id_topic) && createPost( $last_id_topic, $id_category, $post ) ){
            unset( $_SESSION["fields"] );
            header("Location: ?page=home");
        } else {
            $error = "Une erreur est survenue lors de la création du sujet et de son message.";
        }  
    }

    if ($error != '') {
        $error = urlencode( $error );
        header( "Location: ?page=create_topic&error=" . $error );
    }  
?>