<?php
    if ( empty($_GET["id_topic"]) ||
         empty($_GET["index_page"]) ) {
        header("Location: ?page=home");
        die;
    }

    $id_topic = (int) $_GET["id_topic"];
    $index_page = (int) $_GET["index_page"];
    if ( ($id_topic == 0) || ($index_page == 0) ) {//pas un entier
        header("Location: ?page=home");
        die;
    }

    if ( deleteTopic( $id_topic )) {
        header("Location: ?page=home&index_page=" . $index_page);
        die;
    } else {
        $error = "Une erreur est survenue lors de la suppression.";
        $error = urlencode($error);
        header("Location: ?page=home&index_page=" . $index_page . '&error=' . $error);
    }
?>