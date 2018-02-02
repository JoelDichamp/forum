<?php
    
    $paramsGet = [ "index_page_posts" => true, "id_post" => true, "post_date" => false ];

    if ( !checkParamsGet( $paramsGet ) ) {
        header("Location: ?page=home");
        die();
    }

    $params_posts_topic = fill_params_posts_topic( $paramsGet );

    $error = '';
    $params = buildParams_posts_topic($params_posts_topic, true) . 
              "&index_page_posts" . $params_posts_topic["index_page_posts"];
    
    $post = $_POST["post"];
    if (checkPost( $error, $post )) {    
        if ( updatePost($params_posts_topic["id_post"], $post) == -1 ) {
            $error = "Une erreur est survenue lors de la mise a jour du message.";
        } else {
            header("Location: " . $params . '&service_name=update');
        }
    }

    if ($error != '') {
        $error = urlencode($error);
        header("Location: " . $params . "&id_post=" . $params_posts_topic["id_post"] . '&error_update=' . $error . '&service_name=update');
    }

?>