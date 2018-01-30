<?php
session_start();

define("DB_HOST", "localhost");
define("DB_NAME", "forum");
define("DB_USER", "root");
define("DB_PASS", "");

define("TOPICS_BY_PAGE", 20);

// grants
define("CAN_CREATE_TOPIC", 1);
define("CAN_DELETE_TOPIC", 2);
define("CAN_POST_ON_TOPIC", 3);
define("CAN_EDIT_POST", 4);
define("CAN_DELETE_POST", 5);
define("CAN_DELETE_ALL_POST", 6);
define("CAN_CREATE_CATEGORY", 7);

// roles
define("USER", 1);
define("MODERATOR", 2);
define("ADMIN", 3);

// pour les pwd
define("SALT", "QWONQULqF0");

// pagination
define("NB_TOPICS_BY_PAGE", 5);
define("NB_POSTS_BY_PAGE", 3);

//lg champs
define("LG_TOPIC", 60);
define("LG_PSEUDO", 4);
define("LG_PASSWORD", 5);
define("LG_CATEGORY", 30);


function debug( $arg, $printr = false ){
    
    if( $printr ){
        echo "<pre>";
        print_r($arg);
        echo "</pre>";
    }
    else {
        var_dump( $arg );
    }
    die();
}

function getConnection(){

    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if( $errors = mysqli_connect_error($connection) ){
        $errors = utf8_encode($errors);
        header("Location: ?page=login&error=" . $errors); 
        die();
    }

    return $connection;
}

function isLogged( $as_role = USER ){

    return ( 
        isset( $_SESSION["user"] ) 
        && $_SESSION["user"]["id_role"] >= $as_role 
    );

}

function isGranted( $id_role, $id_grant ){

    $connection = getConnection();
    $sql = "SELECT COUNT(*)
    FROM link_role_grant
    WHERE link_role_grant.id_role = ? 
    AND link_role_grant.id_grant = ?";

    $statement = mysqli_prepare( $connection, $sql );
    mysqli_stmt_bind_param( $statement, "ii", $id_role, $id_grant );
    mysqli_stmt_execute( $statement );
    mysqli_stmt_bind_result( $statement, $result );
    mysqli_stmt_fetch( $statement );

    mysqli_stmt_close( $statement );
    mysqli_close( $connection );

    return (boolean)$result; //cast converting
    
}

function calcNbPage($nbItem, $nbItemByPage) {
    $nbPage = (int) ($nbItem / $nbItemByPage);
	if ($nbItem % $nbItemByPage > 0) {
		$nbPage++;
    }

    return $nbPage;
}

function build_close_where_filter_category( &$filter_category, $tableCategories) {
    $close_where = '';
    $filter_category = 0;
    if ( isset($_SESSION["filter_category"]) ) {
        $filter_category = $_SESSION["filter_category"];
    }
    if ( $filter_category > 0 ) {
        if ($tableCategories == '') {
            $close_where = " WHERE id_category=? ";
        } else {
            $close_where = " WHERE categories.id=? ";
        }
        
    }

    return $close_where;
}

function calcNbPageTopics() {
    $connection = getConnection();

    $sql = "SELECT COUNT(*) AS nbItem FROM topics";
    $close_where = build_close_where_filter_category( $filter_category, '' );
 
    if ($close_where) {

        $sql .= " " . $close_where;
        $statement = mysqli_prepare( $connection, $sql );
        mysqli_stmt_bind_param( $statement, "i", $filter_category );
        mysqli_stmt_execute( $statement );
        mysqli_stmt_bind_result( $statement, $result );
        mysqli_stmt_fetch( $statement );
        mysqli_stmt_close( $statement );

    } else {

        $results = mysqli_query($connection, $sql);
        $result = mysqli_fetch_assoc($results);
        $result = $result["nbItem"];

    }
    mysqli_close( $connection );

    return calcNbPage( $result, NB_TOPICS_BY_PAGE );
}

function calcNbPagePosts($id_topic) {
    $connection = getConnection();
    $sql = "SELECT COUNT(*) FROM posts WHERE id_topic=?" ;
    
    $statement = mysqli_prepare( $connection, $sql );
    mysqli_stmt_bind_param( $statement, "i", $id_topic );
    mysqli_stmt_execute( $statement );
    mysqli_stmt_bind_result( $statement, $result );
    mysqli_stmt_fetch( $statement );

    return calcNbPage($result, NB_POSTS_BY_PAGE);
}

function createLogin( $login, &$last_id_login ) {
    $connection = getConnection();
    $sql = "INSERT INTO users VALUES (null, ?, ?, ?, ?)";
    $statement = mysqli_prepare( $connection, $sql );
    $id_role = USER;
    mysqli_stmt_bind_param( 
        $statement, 
        "isss", 
        $id_role,
        $login["pseudo"],
        $login["password"],
        $login["e_mail"] 
    );

    mysqli_stmt_execute( $statement );
    $inserted = mysqli_stmt_affected_rows( $statement );
    $last_id_login = mysqli_insert_id( $connection );

    mysqli_stmt_close( $statement );
    mysqli_close( $connection );

    return (boolean)($inserted > 0);
}

function getLogin( $pseudo, $password ){
    $connection = getConnection();
    $sql = "SELECT * FROM users WHERE pseudo=? AND password=?";
    $statement = mysqli_prepare( $connection, $sql );
    mysqli_stmt_bind_param( $statement, "ss", $pseudo, $password );
    mysqli_stmt_execute( $statement );
    mysqli_stmt_bind_result($statement, $id, $id_role, $pseudo, $password, $e_mail);
    mysqli_stmt_fetch($statement);

    $login = null;
    if( $id ){
        $login = [
            "id" => $id,
            "id_role" => $id_role,
            "pseudo" => $pseudo,
            "password" => $password,
            "e_mail" => $e_mail
        ];
    }

    mysqli_stmt_close( $statement );
    mysqli_close( $connection );

    return $login;
}

function pseudoExistsAlready( $pseudo ) {
    $connection = getConnection();
    $sql = "SELECT COUNT(*) FROM users WHERE pseudo = ?";
    $statement = mysqli_prepare( $connection, $sql );
    mysqli_stmt_bind_param( $statement, "s", $pseudo );
    mysqli_stmt_execute( $statement );
    mysqli_stmt_bind_result( $statement, $result );
    mysqli_stmt_fetch( $statement );

    mysqli_stmt_close( $statement );
    mysqli_close( $connection );

    return (boolean)$result; //cast converting
}

function getCategories() {
    $connection = getConnection();
    $sql = "SELECT * FROM categories ORDER by id";
    $results = mysqli_query( $connection, $sql );
    
    $categories = [];
    while ($row = mysqli_fetch_assoc( $results )) {
        $categories[] = $row;
    }
    mysqli_close( $connection );
    return $categories;
}

function categoryExistsById( $id_category ) {
    $connection = getConnection();
    $sql = "SELECT COUNT(*) FROM categories WHERE id=?" ;
    
    $statement = mysqli_prepare( $connection, $sql );
    mysqli_stmt_bind_param( $statement, "i", $id_category );
    mysqli_stmt_execute( $statement );
    mysqli_stmt_bind_result( $statement, $result );
    mysqli_stmt_fetch( $statement );

    mysqli_stmt_close( $statement );
    mysqli_close( $connection );

    return (boolean)($result > 0);
}

function categoryExists( $category ) {
    $connection = getConnection();
    $sql = "SELECT COUNT(*) FROM categories WHERE category = ?";
    $statement = mysqli_prepare( $connection, $sql );
    mysqli_stmt_bind_param( $statement, "s", $category );
    mysqli_stmt_execute( $statement );
    mysqli_stmt_bind_result( $statement, $result );
    mysqli_stmt_fetch( $statement );
    mysqli_stmt_close( $statement );
    mysqli_close( $connection );

    return (boolean)$result; 
}

function createCategory( $category ) {
    $connection = getConnection();
    $sql = "INSERT INTO categories VALUES (null, ?)";
    $statement = mysqli_prepare( $connection, $sql );
    mysqli_stmt_bind_param($statement,"s", $category);
    mysqli_stmt_execute( $statement );
    $inserted = mysqli_stmt_affected_rows( $statement );
    $last_id_topic = mysqli_insert_id( $connection );
    // $error = mysqli_error( $connection );
    // debug($error);
    mysqli_stmt_close( $statement );
    mysqli_close( $connection );
    
    return (boolean)($inserted > 0);
}

function topicExists( $topic, $id_category ) {
    $connection = getConnection();
    $sql = "SELECT COUNT(*) FROM topics WHERE topic=? AND id_category=?" ;
    
    $statement = mysqli_prepare( $connection, $sql );
    mysqli_stmt_bind_param( $statement, "si", $topic, $id_category );
    mysqli_stmt_execute( $statement );
    mysqli_stmt_bind_result( $statement, $result );
    mysqli_stmt_fetch( $statement );

    mysqli_stmt_close( $statement );
    mysqli_close( $connection );

    return (boolean)($result > 0);
}

function createTopic( $id_category, $topic, &$last_id_topic) {
    $connection = getConnection();
    $sql = "INSERT INTO topics VALUES (null, ?, ?, ?, 1, ?)";
    $statement = mysqli_prepare( $connection, $sql );
    $last_msg_date = date("Y-m-d H:i:s");
    mysqli_stmt_bind_param( 
        $statement, 
        "iiss", 
        $id_category,
        $_SESSION["login"]["id"],
        $topic,
        $last_msg_date
    );

    mysqli_stmt_execute( $statement );
    // $error = mysqli_error( $connection );
    // debug($error);
    $inserted = mysqli_stmt_affected_rows( $statement );
    $last_id_topic = mysqli_insert_id( $connection );

    mysqli_stmt_close( $statement );
    mysqli_close( $connection );
    
    return (boolean)($inserted > 0);
}

function deleteTopic( $id_topic) {
    $connection = getConnection();
    $sql = "DELETE FROM topics WHERE id=?";
    $statement = mysqli_prepare( $connection, $sql );
    mysqli_stmt_bind_param( $statement, "i", $id_topic );
    mysqli_stmt_execute( $statement );
    
    $deleted = mysqli_stmt_affected_rows( $statement );

    mysqli_stmt_close( $statement );
    mysqli_close( $connection );

    return (boolean)($deleted > 0);
}

function createPost( $id_topic, $id_category, $post ) {
    $connection = getConnection();
    $sql = "INSERT INTO posts VALUES (null, ?, ?, ?, ?, ?)";
    $statement = mysqli_prepare( $connection, $sql );
    $post_date = date("Y-m-d H:i:s");
    mysqli_stmt_bind_param( 
        $statement, 
        "iiiss", 
        $id_topic,
        $id_category,
        $_SESSION["login"]["id"],
        $post,
        $post_date
    );

    mysqli_stmt_execute( $statement );
    $inserted = mysqli_stmt_affected_rows( $statement );

    mysqli_stmt_close( $statement );
    mysqli_close( $connection );

    return (boolean)($inserted > 0);
}

function updatePost( $id_post, $post ) {
    $connection = getConnection();
    $sql = "UPDATE posts SET post=? WHERE id=?";
    $statement = mysqli_prepare( $connection, $sql );
    mysqli_stmt_bind_param( $statement, "si", $post, $id_post);
    mysqli_stmt_execute( $statement );
    $edited = mysqli_stmt_affected_rows( $statement );
    
    mysqli_stmt_close( $statement );
    mysqli_close( $connection );

    return $edited;
}

function deletePost($id_post) {
    $connection = getConnection();
    $sql = "DELETE FROM posts WHERE id=?";
    $statement = mysqli_prepare( $connection, $sql );
    mysqli_stmt_bind_param( $statement, "i", $id_post );
    mysqli_stmt_execute( $statement );
    
    $deleted = mysqli_stmt_affected_rows( $statement );

    mysqli_stmt_close( $statement );
    mysqli_close( $connection );

    return (boolean)($deleted > 0);
}

function updateTopicAfterManagePost($id_topic, $ope, $last_msg_date = null) {
    $connection = getConnection();
    // $sql = "UPDATE topics SET nb_posts=nb_posts+1, last_msg_date=? WHERE id=?";
    $sql = "UPDATE topics SET nb_posts=nb_posts" . $ope .
           "1, last_msg_date=? WHERE id=?";
    $statement = mysqli_prepare( $connection, $sql );
    if ( !$last_msg_date ) {
        $last_msg_date = date("Y-m-d H:i:s");
    } 
    mysqli_stmt_bind_param( $statement, "si", $last_msg_date, $id_topic );
    mysqli_stmt_execute( $statement );
    // debug(mysqli_error($connection));
     // -1 erreur | 0 aucun changement | > 0 nombre de lignes affectées
     $edited = mysqli_stmt_affected_rows( $statement );
    
     mysqli_stmt_close( $statement );
     mysqli_close( $connection );
 
     return $edited;
}

function transform_displayDate_to_DBDate( $last_msg_date ) {
    $result = '';
    $t_date = explode( " ", $last_msg_date );
    $elem_date = explode( "/", $t_date[0] );

    $result = $elem_date[2] . "-" . $elem_date[1] . "-" . $elem_date[0];

    $elem_time = str_replace( "h",":", $t_date[1] );
    $elem_time = str_replace("min",":",$elem_time);
    $elem_time = str_replace("s","",$elem_time);

    $result .= " " . $elem_time;
    // date_create_from_format('Y-m-d H:i:s', $last_msg_date);
    return $result;
}

function updateTopicAfterDeletePost($id_topic, $last_msg_date, $post_date ) {
    $result = false;
    $displayDate_to_DBDate = true;
    if ( $last_msg_date == $post_date ) {
        $connection = getConnection();
        $sql = "SELECT post_date FROM posts 
                WHERE id_topic=?
                ORDER BY post_date DESC";
        $statement = mysqli_prepare( $connection, $sql );
        mysqli_stmt_bind_param( $statement, "i", $id_topic );
        mysqli_stmt_execute( $statement );
        mysqli_stmt_bind_result( $statement, $post_date );
        mysqli_stmt_fetch( $statement );

        $last_msg_date = $post_date;

        mysqli_stmt_close( $statement );
        mysqli_close( $connection );
        $displayDate_to_DBDate = false;
    }
    if ( $last_msg_date ) {
        if ( $displayDate_to_DBDate ) {
            $last_msg_date = transform_displayDate_to_DBDate($last_msg_date);
        }
        
        if (updateTopicAfterManagePost( $id_topic, "-", $last_msg_date) >=0 ) {
            $result = true;
        }
    }
    
    return $result;
}

function getTopics( $index_page ) {
    $connection = getConnection();

    $close_where = build_close_where_filter_category( $filter_category, "categories" );

    $sql = "SELECT topics.id,topic,category,categories.id,pseudo,nb_posts,
                   DATE_FORMAT(last_msg_date,'%d/%m/%Y %Hh%imin%ss'),users.id FROM topics 
            JOIN categories ON categories.id=id_category
            JOIN users ON users.id=id_user" . $close_where .
            " ORDER BY last_msg_date DESC
            LIMIT ?, ?";
    $statement = mysqli_prepare( $connection, $sql );

    $start_index = ($index_page-1) * NB_TOPICS_BY_PAGE;
    $end_index = NB_TOPICS_BY_PAGE;

    if ( $close_where) {
        mysqli_stmt_bind_param( $statement, "iii", $filter_category, $start_index, $end_index );
    } else {
        mysqli_stmt_bind_param( $statement, "ii", $start_index, $end_index );
    }
    
    mysqli_stmt_execute( $statement );
    mysqli_stmt_bind_result( $statement, $id, $topic, $category, $id_category, $pseudo, 
                                        $nb_posts, $last_msg_date, $id_user );
    $topics = [];
    while ( mysqli_stmt_fetch( $statement ) ) {
        $topics[] = [
            "id_topic" => $id,
            "topic" => $topic,
            "category" => $category,
            "id_category" => $id_category,
            "pseudo" => $pseudo,
            "nb_posts" => $nb_posts,
            "last_msg_date" => $last_msg_date,
            "id_user" => $id_user
        ];
    }
    mysqli_stmt_close( $statement );
    mysqli_close( $connection );

    return $topics;
}

function getPosts( $id_topic, $index_page ) {
    $connection = getConnection();
    $sql = "SELECT posts.id,posts.id_user,post,pseudo,DATE_FORMAT(post_date,'%d/%m/%Y %Hh%imin%ss') FROM posts
            JOIN users ON users.id=id_user
            WHERE id_topic=?
            ORDER BY post_date DESC
            LIMIT ?, ?";
    $statement = mysqli_prepare( $connection, $sql );
    $start_index = ($index_page-1) * NB_POSTS_BY_PAGE;
    $end_index = NB_POSTS_BY_PAGE;

    mysqli_stmt_bind_param( $statement, "iii", $id_topic, $start_index, $end_index );
    mysqli_stmt_execute( $statement );
    mysqli_stmt_bind_result( $statement, $id, $id_user, $post, $pseudo, $post_date );

    $posts = [];
    while ( mysqli_stmt_fetch($statement) ) {
        $posts[] = [
            "id" => $id,
            "id_user" => $id_user,
            "post" => $post,
            "pseudo" => $pseudo,
            "post_date" => $post_date
        ];
    }
    mysqli_stmt_close( $statement );
    mysqli_close( $connection );

    return $posts;
}

function firstPost( $id_topic ) {
    $connection = getConnection();
    $sql = "SELECT id FROM posts
            WHERE id_topic=?
            ORDER BY post_date ASC";
    //le 1er id sera celui du premier post
    $statement = mysqli_prepare( $connection, $sql );
    mysqli_stmt_bind_param( $statement, "i", $id_topic );
    mysqli_stmt_execute( $statement );
    mysqli_stmt_bind_result( $statement, $id );
    mysqli_stmt_fetch( $statement );
    mysqli_stmt_close( $statement );
    mysqli_close( $connection );

    return $id;
}
?>