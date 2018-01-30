<div id="div_title_page">
    <h3 class="title_page">Créer une catégorie</h3>
    <a class="a_btn" href="?page=home">Liste des sujets</a>
</div>

<div id="div_main_forum">
    <form id="form_create_login" action="?service=create_category" method="POST">
        <fieldset>
            <legend>Catégorie</legend>

            <label for="category_exist" >Catégories existantes</label><br>
            <select id="category_exist">
                <?php echo displayCategories( false, $category ) ?>
            </select><br>

            <label for="category" >* Catégorie</label><br>
                <input type="text" name="category" id="category" value=""
                    placeholder="Saisir la catégorie" size="60" maxlength="60"><br>
                    <input type="submit" value="Valider">

            <?php
            if (isset($_GET["error"])) {
                echo '<scan class="error">' . $_GET["error"] . '</scan>';
            }

            if (isset($_GET["msg"])) {
                echo '<scan class="msg_info">' . $_GET["msg"] . '</scan>';
            }
            ?>
        </fieldset>
    </form>
</div>