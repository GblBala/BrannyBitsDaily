<?php
    setcookie("last_search",$_GET["search"],time() + (30 * 24 * 60 * 60));
    $titre = "En plus";
    $page ="plus.php";
    include "./include/h_and_f/header.inc.php";
    include "./include/fonction.inc.php";
?>
    <main>
        <section>
            <?php
                if(isset($_GET["search"])){
                    $title = $_GET["search"];
                }
                if(isset($_GET["lastsearch"])){
                    $rech = $_GET["lastsearch"];
                }
                else{
                    $rech = $title;
                }
                if(isset($_GET["lastpage"])){
                    $page = $_GET["lastpage"];
                }
                displayBookDetails($title, $rech, $page);
            ?>
        </section>
    </main>

<?php
    include "./include/h_and_f/footer.inc.php";
?>