<?php
    $category = '';
    $topic = '';
    $post = '';
    if( isset( $_SESSION["fields"] ) ){
        if ( isset( $_SESSION["fields"]["category"] ) ) {
            $category = $_SESSION["fields"]["category"];
        }
        if ( isset( $_SESSION["fields"]["topic"] ) ) {
            $topic = $_SESSION["fields"]["topic"];
        }
        if ( isset( $_SESSION["fields"]["topic"] ) ) {
            $post = $_SESSION["fields"]["post"];
        }
    }
?>
<div id="div_title_page">
    <h3 class="title_page">Nouveau sujet</h3>
    <a class="a_btn" href="?page=home">Liste des sujets</a>
</div>
<div id="div_main_forum">
    <form id="form_create_topic" action="?service=create_topic" method="POST">
        <fieldset>
            <legend>Sujet</legend>
            <label for="category" >* Catégorie</label>
            <select name="category" id="category">
                <?php echo displayCategories( false, $category ) ?>
            </select><br>

            <label for="topic" >* Titre</label><br>
            <input type="text" name="topic" id="topic" value="<?php echo $topic ?>"
                    placeholder="Saisir le titre du sujet" size="100" maxlength="255"><br>
            
            <label for="post" >* Message</label><br>
            <textarea name="post" id="post" cols="100" rows="8"><?php echo $post ?></textarea><br>

            <input type="submit" value="Poster">

            <?php
            if (isset($_GET["error"])) {
                echo '<scan class="error">' . $_GET["error"] . '</scan>';
            }
            ?>
        </fieldset>
        
    </form>
</div>