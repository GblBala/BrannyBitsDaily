<?php
    $page="decouvert.php";
    $titre = "Brany Bits Daily";
    include "./include/h_and_f/header.inc.php";
    include "./include/fonction.inc.php";
?>
        <main>
            <section>
                <article>
                    <figure>
                        <?php
                            $photoDir = "./ressource/img";
                            $randomImage = getRandomImage($photoDir);
                            echo "<img src=\"$randomImage\" alt=\"photo de livre\"/>\n";
                        ?>
                    </figure>
                     <form action="recherche.php" method="GET">
                        <input type="text" name="search" id="search" placeholder="Auteur, Titre..." required=""/>
                        <button type="submit">Rechercher</button>
                    </form>
                    <ul>
                        <li><figure><a href="category.php?category=fiction"><img src="./ressource/images/fic.png" alt="images de fiction"/></a><figcaption>Fiction</figcaption></figure></li>
                        <li><figure><a href="category.php?category=science-fiction"><img src="./ressource/images/sciencefic.png" alt="images de science fiction"/></a><figcaption>Science Fiction</figcaption></figure></li>
                        <li><figure><a href="category.php?category=romance"><img src="./ressource/images/rom.png" alt="images de romance"/></a><figcaption>Romance</figcaption></figure></li>
                        <li><figure><a href="category.php?category=mystery"><img src="./ressource/images/myst.png" alt="images de mystery"/></a><figcaption>Mystery</figcaption></figure></li>
                        <li><figure><a href="category.php?category=fantasy"><img src="./ressource/images/fant.png" alt="images de fantasy"/></a><figcaption>Fantatisque</figcaption></figure></li>
                        <li><figure><a href="category.php?category=manga"><img src="./ressource/images/mang.png" alt="images de manga"/></a><figcaption>Manga</figcaption></figure></li>
                    </ul>
                </article>
            </section>
        </main>
<?php
    include "./include/h_and_f/footer.inc.php";
?>