<?php
    $titre = "Statistique de recherche par livre";
    $page ="statistique.php";
    include "./include/h_and_f/header.inc.php";
    include "./include/fonction.inc.php";
?>
        <main>
            <section>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <?php
                    create_bar_chart_from_csv("book_search.csv");
                ?>
            </section>
        </main>
<?php
    include "./include/h_and_f/footer.inc.php";
?>