<?php
    function displayUserRole( &$class_role ) {
        $s_user = " ? ";
        $class_role = '';
        if ( isset($_SESSION["login"]["id_role"]) ) {
            $id_role = $_SESSION["login"]["id_role"];
            switch ($id_role) {
                case USER:
                    $s_user = "Utilisateur"; 
                    $class_role = 'user_role';
                break;

                case MODERATOR:
                    $s_user = "Modérateur"; 
                    $class_role = 'user_moderator';
                break;

                case ADMIN:
                    $s_user = "Administrateur"; 
                    $class_role = 'user_admin';
                break;
            }
        }

        return $s_user;
    }

    function displayCategories($all, $id_category = 0) {
        $categories = getCategories();
        
        $shtml = '';
        if ($all) {

            if ( isset($_SESSION["filter_category"]) ) {
                $id_category = $_SESSION["filter_category"];
            }

            $selected = '';
            if ($id_category == 0) {
                $selected = 'selected';
            }
            $shtml .= '<option value="0" ' . $selected. '>Tous</option>';
        }
        foreach ($categories as $category) {
            // <option value="france">France</option>
            $selected = '';
            if ($category["id"] == $id_category) {
                $selected = 'selected';
            }
            $shtml .= '<option value="' . $category["id"] . '" ' . $selected . '>' . $category["category"] . '</option>';
        }

        return $shtml;
    }

    function checkIndexPage($index_page_name) {
        $index_page = 1;
        if (isset($_GET[$index_page_name])) {
            $index_page = (int) $_GET[$index_page_name];
            if ($index_page == 0) { // $_GET['numPage'] n'est pas entier
                $index_page = 1; // on force la page 1
            }
        }

        return $index_page;
    }

    function buildParams_posts_topic($params_posts_topic, $with_index_Page, $page = "page", $pageName = "posts_topic") {
        $shtml = '?' . $page . '=' .$pageName . '&id_topic=' . $params_posts_topic["id_topic"] . 
                    '&topic=' . $params_posts_topic["topic"] .
                    '&category=' . $params_posts_topic["category"] .
                    '&topic_closed=' . $params_posts_topic["topic_closed"] .
                    '&id_category=' . $params_posts_topic["id_category"] .
                    '&last_msg_date=' . $params_posts_topic["last_msg_date"];

        if ($with_index_Page) {
            $shtml .= '&index_page=' . $params_posts_topic["index_page"];
        }

        return $shtml;
        
    }

    function displayTopics() {
        $index_page = checkIndexPage("index_page");
        $topics = getTopics($index_page);
        $shtml = '';
        foreach ($topics as $key => $topic) {
            if ( $key % 2 == 0) {
                $shtml .= '<tr class="tr_pair">';
            } else {
                $shtml .= '<tr class="tr_impair">';
            }

            $shtml .= '<td><a href="' . buildParams_posts_topic($topic, false) . 
                      '&index_page=' . $index_page . '">' . $topic["topic"] . '</a></td>';
            $shtml .= '<td class="td_center">' . $topic["category"] . '</td>';
            $shtml .= '<td class="td_center">' . $topic["pseudo"] . '</td>';
            $shtml .= '<td class="td_center">' . $topic["nb_posts"] . '</td>';
            $shtml .= '<td class="td_center">' . $topic["last_msg_date"] . '</td>';
            if ( isset($_SESSION["login"]) && isGranted($_SESSION["login"]["id_role"], CAN_DELETE_TOPIC)) {
                $shtml .= '<td><a class="a_del" href="?page=confirm_delete_topic&topic=' . $topic["topic"] . '&id_topic=' . $topic["id_topic"] .
                          '&index_page=' . $index_page . '">S</a></td>';
            }
            $shtml .= '</tr>';
        }

        return $shtml;
    }

    function BuildAnchorManagePost( $action, $params_posts_topic, $index_page_posts, $id_post, $post_date = '' ) {
        $params = '';
        switch ($action) {

            case "Modifier":
                $params = buildParams_posts_topic($params_posts_topic, true);
            break;

            case "Supprimer":
                $params = buildParams_posts_topic($params_posts_topic, true, "service", "delete_post") .
                          '&post_date=' . $post_date;
            break;
        }
        $shtml = '<a class="a_btn" href="' . $params . '&id_post=' . $id_post .
                 '&index_page_posts=' . $index_page_posts . '">'. $action . '</a>';

        return $shtml;
    }

    function createFormUpdate( $params_posts_topic, $post, $index_page_posts, $id_post ) {
        $action = buildParams_posts_topic($params_posts_topic, true, "service", "update_post") .
                  '&index_page_posts=' . $index_page_posts . '&id_post=' . $id_post;
        $shtml = '<form id="form_update_post" action="' . $action . '" method="POST">';
        $shtml.= '<fieldset>';
        $shtml .= '<legend>Modification du message</legend>';
        $shtml .= '<textarea name="post" id="post" cols="100" rows="8">' . $post . '</textarea><br>';
        $shtml .= '<input type="submit" value="Modifier">';

        $params = buildParams_posts_topic($params_posts_topic, true) . '&index_page_posts=' . $index_page_posts;
        $shtml .= '<a class="a_btn" href="' . $params . '">Annuler</a>';

        if (isset($_GET["error_update"])) {
            $shtml .= '<scan class="error">' . $_GET["error_update"] . '</scan>';
        }
        $shtml .= '</fieldset>';
        $shtml .= '</form>';

        return $shtml;
    }

    function editThePost( $post_id, $params_posts_topic, $index_page_posts, &$createFormUpdate) {
        $shtml = BuildAnchorManagePost( "Modifier", $params_posts_topic, $index_page_posts, $post_id );
        if ( ( $params_posts_topic["id_post"] == $post_id && !isset( $_GET["service_name"] ) ) ||
                ( $params_posts_topic["id_post"] == $post_id && isset( $_GET["service_name"] ) &&
                $_GET["service_name"] == "update") ) {

            $createFormUpdate = true;
        }

        return $shtml;
    }

    function deleteThePost($post_id, $post_date, $params_posts_topic, $index_page_posts) {
        $shtml = BuildAnchorManagePost( "Supprimer", $params_posts_topic, $index_page_posts, $post_id, $post_date );
        if ( $params_posts_topic["id_post"] == $post_id && isset($_GET["error_delete"]) && 
             isset($_GET["service_name"]) && $_GET["service_name"] == "delete") {
            $shtml .= '<p class="error">' . $_GET["error_delete"] . '</p>';
        }

        return $shtml;
    }

    function displayPosts( $params_posts_topic ) {
        $index_page_posts = checkIndexPage("index_page_posts");
        $posts = getPosts( $params_posts_topic["id_topic"], $index_page_posts );
        $firstPost = firstPost($params_posts_topic["id_topic"]);
        $shtml = '';
        $deletePost = false;
        foreach ($posts as $post) {
            $createFormUpdate = false;
            $shtml .= '<div class="div_post">';
            $shtml .= '<span class="pseudo">' . $post["pseudo"] . ' le ' .  $post["post_date"] . '</span>';

            if (!$params_posts_topic["topic_closed"]) { //si le sujet n'est pas fermé
                if ( isset($_SESSION["login"]) ) { //on est loggé
                    if ( $post["id_user"] == $_SESSION["login"]["id"] ) { //c'est est un des posts du user

                        if ( isGranted( $_SESSION["login"]["id_role"], CAN_EDIT_POST ) ) {
                            $shtml .= editThePost( $post["id"], $params_posts_topic, $index_page_posts, $createFormUpdate );
                            $deletePost = ( isGranted($_SESSION["login"]["id_role"], CAN_DELETE_POST) ||
                                            isGranted($_SESSION["login"]["id_role"], CAN_DELETE_ALL_POST) );    
                        }

                    } else {
                        $deletePost = isGranted($_SESSION["login"]["id_role"], CAN_DELETE_ALL_POST);
                    }
                }

                if ( $deletePost && $post["id"] != $firstPost ) { //on a le droit de supprimer et on n'est pas sur le post originel
                    $shtml .= deleteThePost( $post["id"], $post["post_date"], $params_posts_topic, $index_page_posts );
                }
            }
            
            $shtml .= '<hr>';
            $shtml .= '<p>' . nl2br($post["post"]) . '</p>';
            $shtml .= '</div>';
            if ($createFormUpdate) {
                $shtml .= createFormUpdate( $params_posts_topic, $post["post"], $index_page_posts, $post["id"] );
            }
        }

        return $shtml;
    }

    function BuildAnchorTopics($nPage, $index_page, $classValue, $txtAnchor) {
        if (isset($classValue)) {
            $anchor = '<a class="' . $classValue . '"';
        } else {
            $anchor = '<a';
        }
    
        if ($classValue == 'aPageNum') {
            if ($nPage == $index_page) {
                $anchor .= ' style="color:black;font-weight:bold"';
            }
        }
        $anchor .= ' href="?page=home&index_page=' . $nPage . '">'; 
    
        if (isset($txtAnchor)) {
            $anchor .=  $txtAnchor;
        } else {
            $anchor .= $nPage;
        }
        $anchor .= "</a>";
        
        return $anchor;
    }

    function CalcViewPageInfAndSup($numPage, $nbPage, $nbViewPage , &$viewPageInf, &$viewPageSup) {
        $nbViewPageDiv2 = (int) ($nbViewPage / 2);
        $viewPageMedianInf = $nbViewPageDiv2 + 1;
        $viewPageMedianSup = $nbPage - $nbViewPageDiv2;
    
        if ($nbPage <= $nbViewPage) {
            $viewPageInf = 1;
            $viewPageSup = $nbPage;
        } else {
            if ($numPage <= $viewPageMedianInf) {
                $viewPageInf = 1;
                $viewPageSup = $nbViewPage;
                if ($nbPage < $nbViewPage) {
                    $viewPageSup = $nbPage;
                }
            } else {
                if ($numPage <= $viewPageMedianSup) {
                    $viewPageInf = $numPage - $nbViewPageDiv2;
                    $viewPageSup = $numPage + $nbViewPageDiv2;
                    if ($numPage + $nbViewPageDiv2 > $nbPage) {
                        $viewPageSup = $nbPage;
                    }	
                } else {
                    $viewPageInf = $nbPage - $nbViewPage + 1;
                    $viewPageSup = $nbPage;
                }
            }
        }  
    }

    function BuildListPagesTopics($index_page, $nbPage) {
        $nbViewPage = 7; //impair c'est mieux
        CalcViewPageInfAndSup($index_page, $nbPage, $nbViewPage, $viewPageInf, $viewPageSup);
        //echo $index_page . ' ' . $nbPage . ' ' . $nbViewPage . ' ' . $viewPageInf . ' ' . $viewPageSup;
        //echo '<br />';
    
        $divPages = '<div id="divPages">';
        
        if ($index_page > 1) {
            $divPages .= BuildAnchorTopics($index_page-1, $index_page, 'aPagePred', 'Précédent');
        }
    
        for ($i = $viewPageInf; $i <= $viewPageSup; $i++) {
            $divPages .= BuildAnchorTopics($i, $index_page, 'aPageNum', null);
        }
    
        if ($index_page < $nbPage) {
            $divPages .= BuildAnchorTopics($index_page+1, $index_page, 'aPageNext', 'Suivant');
        }
        $divPages .= '</div>';
        
        return $divPages;
    }

    function PutNavPagesTopics($index_page) {
        $nbPage = calcNbPageTopics();

        $divPages = '';
        if ($nbPage > 0) {
            $divPages = BuildListPagesTopics($index_page, $nbPage);
        }

        return $divPages;
    }

    function BuildAnchorPosts($params_posts_topic, $nPage, $index_page_posts, $classValue, $txtAnchor) {
        if (isset($classValue)) {
            $anchor = '<a class="' . $classValue . '"';
        } else {
            $anchor = '<a';
        }
    
        if ($classValue == 'aPageNum') {
            if ($nPage == $index_page_posts) {
                $anchor .= ' style="color:black;font-weight:bold"';
            }
        }
        $anchor .= ' href="' . buildParams_posts_topic($params_posts_topic, true) .
                   '&index_page_posts=' . $nPage .'">'; 
    
        if (isset($txtAnchor)) {
            $anchor .=  $txtAnchor;
        } else {
            $anchor .= $nPage;
        }
        $anchor .= "</a>";
        
        return $anchor;
    }

    function BuildListPagesPosts($index_page_posts, $nbPage, $params_posts_topic) {
        $nbViewPage = 7; //impair c'est mieux
        CalcViewPageInfAndSup($index_page_posts, $nbPage, $nbViewPage, $viewPageInf, $viewPageSup);
        //echo $index_page . ' ' . $nbPage . ' ' . $nbViewPage . ' ' . $viewPageInf . ' ' . $viewPageSup;
        //echo '<br />';
    
        $divPages = '<div id="divPages">';
        
        if ($index_page_posts > 1) {
            $divPages .= BuildAnchorPosts($params_posts_topic, $index_page_posts-1, $index_page_posts, 'aPagePred', 'Précédent');
        }
    
        for ($i = $viewPageInf; $i <= $viewPageSup; $i++) {
            $divPages .= BuildAnchorPosts($params_posts_topic, $i, $index_page_posts, 'aPageNum', null);
        }
    
        if ($index_page_posts < $nbPage) {
            $divPages .= BuildAnchorPosts($params_posts_topic, $index_page_posts+1, $index_page_posts, 'aPageNext', 'Suivant');
        }
        $divPages .= '</div>';
        
        return $divPages;
    }

    function PutNavPagesPosts($index_page_posts, $params_posts_topic) {
        $nbPage = calcNbPagePosts($params_posts_topic["id_topic"]);

        return BuildListPagesPosts($index_page_posts, $nbPage, $params_posts_topic);
    }

    function checkPost( &$error, &$post ) {
        $ok = false;
        if (empty($_POST["post"])) {
            $error = "Le message n'a pas été saisi !";
        } else {
            $post = $_POST["post"];
            if ( strlen($post) > 2000) {
                $error = "La longueur du message doit être inférieure ou égale à 2000 caractères !";
            } else {
                $post = htmlspecialchars($post);
                $ok = true;
            }  
        }

        return $ok;
    }

    function checkParamsGet( $paramsGet ) {
        $ok = true;
        //params de base
        if ( !filter_var( $_GET["id_topic"], FILTER_VALIDATE_INT ) ||
             !isset( $_GET["topic"] ) ||
             !isset( $_GET["topic_closed"]) || //bug avec filter_var sur valeur 0, retourne false !!! (voir PHP manuel)
             !isset( $_GET["category"] ) ||
             !filter_var( $_GET["id_category"], FILTER_VALIDATE_INT ) ||
             !isset( $_GET["last_msg_date"] ) ||
             !filter_var( $_GET["index_page"], FILTER_VALIDATE_INT ) ) {
            
            $ok = false;
        }
        if ($ok) {
            
            if ( $paramsGet["index_page_posts"] ) {
                $ok = filter_var( $_GET["index_page_posts"], FILTER_VALIDATE_INT );
            }
            if ( $ok && $paramsGet["id_post"] ) {
                $ok = filter_var( $_GET["id_post"], FILTER_VALIDATE_INT );
            }
            if ( $ok && $paramsGet["post_date"] ) {
                $ok = isset( $_GET["post_date"] ); 
            }
        }

        return $ok;
    }

    function fill_params_posts_topic( $paramsGet ) {
        $params_posts_topic = [
            "id_topic" => $_GET["id_topic"],
            "topic" => $_GET["topic"],
            "topic_closed" => $_GET["topic_closed"],
            "category" => $_GET["category"],
            "id_category" => $_GET["id_category"],
            "last_msg_date" => $_GET["last_msg_date"],
            "index_page" => $_GET["index_page"],
        ];

        if ( $paramsGet["index_page_posts"] ) {
            $params_posts_topic ["index_page_posts"] = $_GET["index_page_posts"];
        }
        if ( $paramsGet["id_post"] ) {
            $params_posts_topic ["id_post"] = $_GET["id_post"];
        }
        if ( $paramsGet["post_date"] ) {
            $params_posts_topic ["post_date"] = $_GET["post_date"];
        }

        return $params_posts_topic;
    }
?>