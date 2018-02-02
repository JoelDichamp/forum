<?php
    
    $paramsGet = [ "index_page_posts" => true, "id_post" => true, "post_date" => true ];

    if ( !checkParamsGet( $paramsGet ) ) {
        header("Location: ?page=home");
        die();
    }

    $params_posts_topic = fill_params_posts_topic( $paramsGet );

    $error = '';
    $params = buildParams_posts_topic($params_posts_topic, true) . 
            "&index_page_posts" . $params_posts_topic["index_page_posts"];

    if ( deletePost($params_posts_topic["id_post"]) && 
        updateTopicAfterDeletePost($params_posts_topic["id_topic"], $params_posts_topic["last_msg_date"], $params_posts_topic["post_date"] )) {
        header("Location: " . $params . '&service_name=delete');
    } else {
        $error = "Une erreur est survenue lors de la suppression du message.";
    }

    if ($error != '') {
        $error = urlencode($error);
        header("Location: " . $params . "&id_post=" . $params_posts_topic["id_post"] . '&error_delete=' . $error . '&service_name=delete');
    }
?>