<?php
    function categoryOK( &$category, &$error ) {
        $ok = false;
        if ( empty($_POST["category"]) ) {
            $error = "La catégorie n'a pas été saisie !";
        } else {
            //taille limitée à LG_CATEGORY 
            $category = trim($_POST["category"]);
            if ( strlen( $category ) > LG_CATEGORY ) {
                $error = "La catégorie ne doit pas excéder " . LG_CATEGORY . " caractères !";
            } else {    
                //existe en DB ?
                if ( categoryExists( $category ) ) {
                    $error = "La catégorie '" . $category ."' saisie existe déjà !";
                } else {
                    $category = htmlspecialchars($category);
                    $ok = true;
                }
            }
        }

        return $ok;
    }

    $error = '';
    if ( categoryOk( $category, $error) ) {
        if ( createCategory( $category ) ) {
            header("Location:  ?page=create_category&msg=Catégorie '" . $category. "' créée !");
        } else {
            $error = "Une erreur est survenue lors de la création de la catégorie.";
        }
    }

    if ($error != '') {
        $error = urlencode($error);
        header("Location: ?page=create_category&error=" . $error);
    } 
?>