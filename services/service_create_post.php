<?php   
    
    $paramsGet = [ "index_page_posts" => false, "id_post" => false, "post_date" => false ];
    if ( !checkParamsGet( $paramsGet ) ) {
        header("Location: ?page=home"); 
        die();
    }

    $params_posts_topic = fill_params_posts_topic( $paramsGet );
    
    $error = '';
    $params = buildParams_posts_topic($params_posts_topic, true);
    
    if (checkPost( $error, $post )) {     
        if (createPost( $params_posts_topic["id_topic"], $params_posts_topic["id_category"], $post ) && 
            updateTopicAfterManagePost($params_posts_topic["id_topic"], "+") ) {
            header("Location: " . $params);
        } else {
            $error = "Une erreur est survenue lors de la création du message.";
        }  
    }

    if ($error != '') {
        $error = urlencode($error);
        header("Location: " . $params . '&error=' . $error);
    }    
?>