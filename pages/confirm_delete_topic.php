<?php
    if ( !filter_var( $_GET["id_topic"], FILTER_VALIDATE_INT ) ||
         !isset( $_GET["topic"] ) ||
         !filter_var( $_GET["index_page"], FILTER_VALIDATE_INT ) ) {

        header("Location: ?page=home");
        die();
    }

    $id_topic = $_GET["id_topic"];
    $topic = $_GET["topic"];
    $index_page = $_GET["index_page"];
?>

<div id="div_title_page">
    <h3 class="title_page">Confirmation</h3>
    <a class="a_btn" href="?page=home&index_page=<?php echo $index_page ?>">Liste des sujets</a>
</div>

<div id="div_main_forum">
    <form id="form_confirm" 
        action="?service=delete_topic&id_topic=<?php echo $id_topic ?>&topic=<?php echo $topic ?>&index_page=<?php echo $index_page ?>"
        method="POST">
        <fieldset>
            <legend>Confirmation</legend>

            <label>Suppression du sujet '<?php echo $topic ?>' :</label><br>
            <div id="div_radio">
                <input type="radio" name="conf" value="oui" id="oui" checked><label id="label_oui" for="oui">Oui</label>

                <input type="radio" name="conf" value="non" id="non"><label id="label_non" for="non">Non</label>
            </div>
            
            <input type="submit" value="OK">

            <?php
            if (isset($_GET["error"])) {
                echo '<scan class="error">' . $_GET["error"] . '</scan>';
            }
            ?>
        </fieldset>
    </form>
</div>