<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="assets/css/styles.css">
    </head>
    <body>
        <div id=div_head>
            <h2>LaVieDuDev.com</h2>
            <a class="a_btn" href="?page=login">Mon compte</a>
            <?php
                if ( isset($_SESSION["login"]) ) {
                    $id_role = displayUserRole( $class_role );
                    echo "<h3>Bienvenue " . $_SESSION["login"]["pseudo"] . ' (<span class="' . $class_role . '">' . $id_role . "</span>)</h3>";
                }
            ?>
        </div>

        