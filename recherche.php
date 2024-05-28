<?php
    $titre = "Voici les résultats de votre recherche";
    $page ="recherche.php";
    include "./include/h_and_f/header.inc.php";
    include "./include/fonction.inc.php";
?>
        <main>
            <section>
                <article class="form">
                    <?php
                        if(isset($_GET["search"])){
                            $search = $_GET["search"];
                            str_replace("+", " ", $search);
                        }
                        else if (isset($_GET["lastsearch"])){
                            $search = $_GET["lastsearch"];
                            str_replace("+", " ", $search);
                        }
                        else {
                            $search = "";
                        }
                        echo "<form action=\"recherche.php\" method=\"GET\">\n";
                            echo "\t<input type=\"text\" name=\"search\" id=\"search\" placeholder=\"stephen king\" value=\"".htmlspecialchars($search)."\" required=\"\"/>\n";
                            echo "\t<button type=\"submit\">Rechercher</button>\n";
                        echo "</form>\n";
                    ?>
                </article>
                <?php
                    if(isset($_GET["search"])){
                        enregistrerTitre($_GET["search"]);
                    }
                    if(isset($_GET["search"])){
                        $rech=$_GET["search"];
                        $rech = preg_replace('/\s+/', '+', $rech);
                    }
                    if(isset($_GET["lastsearch"])){
                        $rech=$_GET["lastsearch"];
                        $rech = preg_replace('/\s+/', '+', $rech);
                    }
                    if(!isset($_GET["page"])){
                        $p = 0;
                    }
                    else{
                        $p = intval($_GET["page"]);
                    }
                    
                    $result = extractionApiJson("https://www.googleapis.com/books/v1/volumes?q=".$rech."&key=AIzaSyCNN3bc5VCL82r7DU5q7JSWSe3e2FHcp1s&maxResults=20&orderBy=relevance&startIndex=".strval($p*20));
                    if(isset($result["totalItems"])){
                        if(isset($_GET["page"])){
                            echo changPage($rech,$result["totalItems"],$page, $p);
                        }
                        else{
                            echo changPage($rech,$result["totalItems"],$page);
                        }                  
                        echo search_book($result, $page);
                        if(isset($_GET["page"])){
                            echo changPage($rech,$result["totalItems"],$page, $p);
                        }
                        else{
                            echo changPage($rech,$result["totalItems"],$page);
                        }     
                    }
                    else{
                        echo "<article>\n";
                        echo "\t<p>Il n'y a pas de livre en liens avec la recherche.</p>\n";
                        echo "</article>\n";
                    }
                ?>
            </section>
        </main>
<?php
    include "./include/h_and_f/footer.inc.php";
?>      