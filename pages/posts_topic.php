<?php   
    // if ( !isset($_GET["id_topic"]) ||
    //      !isset($_GET["topic"])    ||
    //      !isset($_GET["category"]) ||
    //      !isset($_GET["id_category"]) ||
    //      !isset($_GET["last_msg_date"]) ||
    //      !isset($_GET["id_user"]) ||
    //      !isset($_GET["index_page"]) ) {
    //      header("Location: ?page=home");
    //      die();
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
    //     "id_category" => $_GET["id_category"],
    //     "last_msg_date" => $_GET["last_msg_date"],
    //     "id_user" => $_GET["id_user"],
    //     "index_page" => $_GET["index_page"],
    //     "id_post" => 0
    // ];

    $param_posts_topic = fill_param_posts_topic( $paramsGet );
    $param_posts_topic["id_post"] = 0;

    if ( isset($_GET["id_post"]) ) {
        $param_posts_topic["id_post"] = $_GET["id_post"];
    }

?>
<div id="div_main_forum">
    <div id="head_posts">
        <h3 class="title_topic">Sujet : <?php echo $param_posts_topic["topic"] .
             '    [ <span class="title_category">' . $param_posts_topic["category"] . '</span> ]'?></h3>
        <a class="a_btn" href="?page=home&index_page=<?php echo $param_posts_topic["index_page"] ?>">Liste des sujets</a>
    </div>

    <div id="pages">
        <?php
            $index_page_posts = checkIndexPage("index_page_posts");
            echo PutNavPagesPosts($index_page_posts, $param_posts_topic);
        ?>
    </div>

    <?php
            if ( isset($_SESSION["login"]) ) {
        ?>
        <form id="form_create_post" 
            action="<?php echo BuildParams_posts_topic($param_posts_topic, true, "service", "create_post")?>"           
            method="POST">
            <fieldset>
                <legend>Répondre</legend>
                <textarea name="post" id="post" cols="100" rows="8"></textarea><br>

                <input type="submit" value="Poster">    
                <?php
                    if (isset($_GET["error"])) {
                        echo '<scan class="error">' . $_GET["error"] . '</scan>';
                    }
                ?>
            </fieldset>
        </form>
    <?php } ?>

    <?php
        echo displayPosts( $param_posts_topic );
    ?>
</div>