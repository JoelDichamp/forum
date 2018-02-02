<?php
    if ( !filter_var( $_GET["id_topic"], FILTER_VALIDATE_INT ) ||
         !isset( $_GET["topic"] ) ||
         !filter_var( $_GET["index_page"], FILTER_VALIDATE_INT ) ) {

        header("Location: ?page=home");
        die();
    }  

    $id_topic = $_GET["id_topic"];
    $topic = $_GET["topic"];
    $index_page = $_GET["index_page"];

    if ( $_POST["conf"] == "non") {
        header("Location: ?page=home&index_page=" . $index_page);
        die();
    }
    
    if ( deleteTopic( $id_topic )) {
        header("Location: ?page=home&index_page=" . $index_page);
    } else {
        $error = "Une erreur est survenue lors de la suppression.";
        $error = urlencode($error);
        header("Location: ?page=confirm_delete_topic&id_topic=" . $id_topic . "&topic=" . $topic . "&index_page=" . $index_page . '&error=' . $error);
    }  
?>