<?php   
    // if (!isset($_GET["id_topic"]) ||
    //     !isset($_GET["topic"])    ||
    //     !isset($_GET["category"]) ||
    //     !isset($_GET["id_category"]) ||
    //     !isset($_GET["last_msg_date"]) ||
    //     !isset($_GET["index_page"])) {
    //     header("Location: ?page=home");
    //     die();
    // } 

    $paramsGet = [ "index_page_posts" => false, "id_post" => false, "post_date" => false ];
    if ( !checkParamsGet( $paramsGet ) ) {
        header("Location: ?page=home"); 
        die();
    }

    // $param_posts_topic = [
    //     "id_topic" => $_GET["id_topic"],
    //     "topic" => $_GET["topic"],
    //     "category" => $_GET["category"],
    //     "last_msg_date" => $_GET["last_msg_date"],
    //     "id_category" => $_GET["id_category"],
    //     "index_page" => $_GET["index_page"]
    // ];

    $param_posts_topic = fill_param_posts_topic( $paramsGet );
    
    $error = '';
    $params = BuildParams_posts_topic($param_posts_topic, true);
    
    if (checkPost( $error, $post )) {     
        if (createPost( $param_posts_topic["id_topic"], $param_posts_topic["id_category"], $post ) && 
            updateTopicAfterManagePost($param_posts_topic["id_topic"], "+") ) {
            header("Location: " . $params);
        } else {
            $error = "Une erreur est survenue lors de la création du post.";
        }  
    }

    if ($error != '') {
        $error = urlencode($error);
        header("Location: " . $params . '&error=' . $error);
    }    
?>