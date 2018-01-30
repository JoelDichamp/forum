<?php
    $e_mail = "";
    $pseudo = "";
    $password = "";
    $password_again = "";
    if( isset( $_SESSION["fields"] ) ){
        if (isset($_SESSION["fields"]["e_mail"])) {
            $e_mail = $_SESSION["fields"]["e_mail"];
        }
        if (isset($_SESSION["fields"]["pseudo"])) {
            $pseudo = $_SESSION["fields"]["pseudo"];
        }
        if (isset($_SESSION["fields"]["password"])) {
            $password = $_SESSION["fields"]["password"];
        }
        if (isset($_SESSION["fields"]["password"])) {
            $password_again = $_SESSION["fields"]["password_again"];
        }
    }
?>
<div id="div_title_page">
    <h3 class="title_page">Ajouter un compte</h3>
    <a class="a_btn" href="?page=home">Liste des sujets</a>
</div>

<div id="div_main_forum">
    <form id="form_create_login" action="?service=create_login" method="POST">
        <fieldset>
            <legend>Compte</legend>
            <label for="e_mail" >* Addresse e-mail</label><br>
            <input type="text" name="e_mail" id="e_mail" value="<?php echo $e_mail ?>"
                placeholder="Votre adresse e-mail" size="60" maxlength="255"><br>

            <label for="pseudo" >* Pseudo</label><br>
            <input type="text" name="pseudo" id="pseudo" value="<?php echo $pseudo ?>"
                placeholder="Votre pseudo (au moins 4 caractères)" size="60" maxlength="60"><br>

            <label for="password" >* Mot de passe</label><br>
            <input type="password" name="password" id="password" value="<?php echo $password ?>"
                placeholder="Votre mot de passe (au moins 5 caractères)" size="60" maxlength="60">
            <input type="password" name="password_again" id="password_again" value="<?php echo $password_again ?>"
                placeholder="Retapez votre mot de passe" size="60" maxlength="60"><br>

            <input type="submit" value="Valider">

            <?php
            if (isset($_GET["error"])) {
                echo '<scan class="error">' . $_GET["error"] . '</scan>';
            }
            ?>
        </fieldset>
    </form>
</div>