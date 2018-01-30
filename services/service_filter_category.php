<?php
    $ok = false;

    if ( isset($_POST["category"]) ) {
        if ( empty($_POST["category"]) ) {
            if ($_POST["category"] == 0) {
                $id_category = $_POST["category"];
                $ok = true;
            }
        } else {
            $id_category = (int) $_POST["category"]; //si non entier alors retourene 0 
            $ok = true;
        }
    }
    if (!$ok) {
        header("Location: ?page=home");
        die();
    }

    $_SESSION["filter_category"] = $id_category;
    header("Location: ?page=home");
?>