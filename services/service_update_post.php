<?php
    // if (!isset($_GET["id_topic"]) ||
    //     !isset($_GET["topic"])    ||
    //     !isset($_GET["category"]) ||
    //     !isset($_GET["id_category"]) ||
    //     !isset($_GET["last_msg_date"]) ||
    //     !isset($_GET["id_user"]) ||
    //     !isset($_GET["index_page"]) ||
    //     !isset($_GET["index_page_posts"]) ||
    //     !isset($_GET["id_post"]) ){
    //     header("Location: ?page=home");
    //     die();
    // } 

    $paramsGet = [ "index_page_posts" => true, "id_post" => true, "post_date" => false ];

    if ( !checkParamsGet( $paramsGet ) ) {
        header("Location: ?page=home");
        die();
    }

    // $param_posts_topic = [
    //     "id_topic" => $_GET["id_topic"],
    //     "topic" => $_GET["topic"],
    //     "category" => $_GET["category"],
    //     "id_category" => $_GET["id_category"],
    //     "last_msg_date" => $_GET["last_msg_date"],
    //     "id_user" => $_GET["id_user"],
    //     "index_page" => $_GET["index_page"],
    //     "index_page_posts" => $_GET["index_page_posts"],
    //     "id_post" => $_GET["id_post"]
    // ];

    $param_posts_topic = fill_param_posts_topic( $paramsGet );

    $error = '';
    $params = BuildParams_posts_topic($param_posts_topic, true) . 
              "&index_page_posts" . $param_posts_topic["index_page_posts"];
    
    $post = $_POST["post"];
    if (checkPost( $error, $post )) {    
        if ( updatePost($param_posts_topic["id_post"], $post) == -1 ) {
            $error = "Une erreur est survenue lors de la mise a jour.";
        } else {
            header("Location: " . $params . '&service_name=update');
        }
    }

    if ($error != '') {
        $error = urlencode($error);
        header("Location: " . $params . "&id_post=" . $param_posts_topic["id_post"] . '&error_update=' . $error . '&service_name=update');
    }

?>