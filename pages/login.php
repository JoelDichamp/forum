<div id="div_title_page">
    <h3 class="title_page">Connection</h3>
    <a class="a_btn" href="?page=home">Liste des sujets</a>
</div>
<div id="div_main_forum">
    <div id="connection">
        <div id="login">
            <form id="form_login" action="?service=login" method="POST">
                <fieldset>
                    <legend>Se connecter</legend>
                    <input type="text" name="pseudo" placeholder="Pseudo" size="60" maxlength="60"><br>
                    <input type="password" name="password" placeholder="Mot de passe" size="60" maxlength="60"><br>

                    <input type="submit" value="Valider">

                    <?php
                        if (isset($_GET["error"])) {
                            echo '<scan class="error">' . $_GET["error"] . '</scan>';
                        }
                    ?>
                </fieldset>
            </form>
        </div>

        <div id="create_login">
            <h3>Vous n'avez pas de pseudo sur LaVieDuDev.com ?</h3>
            <a class="a_btn" href="?page=create_login">Créer un compte</a>
        </div>
    </div>
</div>