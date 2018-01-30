<div id="div_title_page">
    <h3 class="title_page_home">Forum communauté</h3>
</div>
<div id="div_main_forum">
    <div id="div_forms_forum">
        <?php
            if ( isset($_SESSION["login"]) ) {
        ?>
            <form class="form_forum" id="form_topic" action="?page=create_topic" method="POST">
                <input type="submit" value="Nouveau sujet">
            </form>
        <?php } ?>

        <?php
            if ( isset($_SESSION["login"]) && isGranted($_SESSION["login"]["id_role"], CAN_CREATE_CATEGORY) ) {
        ?>
            <form class="form_forum" id="form_topic" action="?page=create_category" method="POST">
                <input type="submit" value="Nouvelle catégorie">
            </form>
        <?php } ?>

        <form class="form_forum" id="form_filter_category" action="?service=filter_category" method="POST">
            <select name="category" id="category">
                <?php echo displayCategories( true ) ?>
            </select>
            <input type="submit" value="OK"> 
        </form>

        <form id="form_search" action="" >
            <input type="text" name="txt_search" placeholder="Not yet implemented" size="50" maxlength="50">
            <select name="type_search" id="type_search">
                <option value="type_search1">Sujet</option>
                <option value="type_search2">Auteur</option>
                <option value="type_search3">Message</option>
            </select> 
            <input type="submit" value="Rechercher">
        </form>
    </div>
    
    <div id="pages">
        <?php
            $index_page = checkIndexPage("index_page");
            echo PutNavPagesTopics($index_page);
        ?>
    </div>

    <table id="t_topics">
        <tr>
            <th class="head_topic">Sujet</th>
            <th class="head_category">Catégorie</th>
            <th class="head_author">Auteur</th>
            <th class="head_nb">NB</th>
            <th class="head_last_msg">Dernier msg</th> 
            <?php 
                if ( isset($_SESSION["login"]) && isGranted($_SESSION["login"]["id_role"], CAN_DELETE_TOPIC)) { ?>
                    <th class="head_delete_topic">Suppression</th>
            <?php } ?> 
        </tr>
        <?php echo displayTopics() ?>
    </table>
    
</div>

<?php
    
?>