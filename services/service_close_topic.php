<?php
    $paramsGet = [ "index_page_posts" => false, "id_post" => false, "post_date" => false ];
    if ( !checkParamsGet( $paramsGet ) ) {
        header("Location: ?page=home"); 
        die();
    }

    $params_posts_topic = fill_params_posts_topic( $paramsGet );

    if ( reopenCloseTopic( $params_posts_topic["id_topic"], $params_posts_topic["topic_closed"] ) ) {
        //attention : reopenCloseTopic modifie $params_posts_topic["topic_closed"]
        header("Location: " . buildParams_posts_topic($params_posts_topic, true));
    } else {
        if ($params_posts_topic["topic_closed"]) {
            $txt = "réouverture";
        } else {
            $txt = "fermeture";
        }
        $error = "Une erreur est survenue lors de la " . $txt . " du message.";
        $error = urlencode($error);
        header("Location: " . buildParams_posts_topic($params_posts_topic, true) . '&error=' . $error);
    }
?>