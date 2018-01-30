<?php 
    require "functions.php";
    require "display_fcts.php";

    /* Service */
    // mon/url.php?service=nom_du_service

    if( isset( $_GET["service"] ) ){

        $service = $_GET["service"];
        switch( $service ){

            case "login": 
                include "services/service_login.php";
                break;
            
            case "filter_category": 
                include "services/service_filter_category.php";
                break;

            case "create_login": 
                include "services/service_create_login.php";
                break;

            case "create_topic": 
                include "services/service_create_topic.php";
                break;

            case "delete_topic": 
                include "services/service_delete_topic.php";
                break;

            case "create_post": 
                include "services/service_create_post.php";
                break;

            case "update_post": 
                include "services/service_update_post.php";
                break;

            case "delete_post": 
                include "services/service_delete_post.php";
                break;

            case "create_category": 
                include "services/service_create_category.php";
                break; 

            default :
                header("Location: ?page=login");
        }  
        die();
    }

    /* Pages */

    $page = "home";
    $page_file = "";

    if( isset( $_GET["page"] ) ){
        $page = $_GET["page"];
    }

    switch( $page ){

        case "home":
            $page_file = "pages/home.php";
            break;
        case "login":
            $page_file = "pages/login.php";
            break;
        case "create_login":
            $page_file = "pages/create_login.php";
            break;
        case "create_topic":
            $page_file = "pages/create_topic.php";
            break;
        case "posts_topic":
            $page_file = "pages/posts_topic.php";
            break;
        case "create_category": 
            $page_file = "pages/create_category.php";
            break; 

        default:
            $page_file = "pages/404.php";

    }

    include "commons/header.php";
    include $page_file;
    include "commons/footer.php";

?>