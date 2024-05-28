<?php
    $page="index.php";
    $titre = "Brany Bits Daily";
    include "./include/h_and_f/header.inc.php";
    include "./include/fonction.inc.php";
?>
        <main>
            <section>
                <h2>
                    Best-seller
                </h2>
                <div class ="sect">
                    <?php
                        getBestSellers();
                    ?>
                </div>
            </section>
            <section>
                <h2>
                    Votre dernière recherche
                </h2>
                <?php
                    if(isset($_COOKIE["last_search"])){
                        affLastSearch($_COOKIE["last_search"]);
                    }
                    else{
                        echo "Vous n'avez pas recherché de livre.";
                    }
                ?>
            </section>
        </main>
<?php
    include "./include/h_and_f/footer.inc.php";
?>