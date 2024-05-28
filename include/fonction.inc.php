<?php
    declare(strict_types=1);

    /**
     * @author
     */
    
    /**
     * Cette fonction a pour but d'extraire un flux JSON en array
     * @param (string) $url contenant l'url que l'on souhaite traiter
     * @return (array) $data contenant les informations que l'on veut
     */
    function extractionApiJSON(string $url):array{
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($curl);
        if($data == false){
            echo curl_error($curl);
        }
        else{
            $data = json_decode($data, true);
        }
        curl_close($curl);
        return $data;
    }

    /**
     * Cette fonction a pour but d'extraire un flux XML en SimpleXMLElement
     * @param (string) $url contenant l'url que l'on souhaite traiter
     * @return (SimpleXMLElement) $parse contenant les informations que l'veut
     */
    function extractionApiXML(string $url): SimpleXMLElement{
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $xml = curl_exec($curl);
        if($xml == false){
            echo curl_error($curl);
        }
        else{
            $parse = simplexml_load_string($xml);
        }
        curl_close($curl);
        return $parse;
    }

    /**
     * Cette fonction permet l'affichage d'un date
     * @param (string) $lang qui est la lang dans laquelle on va afficher la date
     * @return (string) $s qui contient la date dans la langue souhaiter
     */
    function dateAff(string $lang):String{
        $s ="";
        if($lang == "en"){
            $s = date("l, F j, o");
        }
        else if($lang =="fr"){
            $jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
            $mois = array(1=>"Janvier","Fevrier","Mars","Avril","Mai","Juin","Juillet","Aout","Septembre","Octobre","Novembre","Decembre");
            $s = $jour[date("w")]."&#x00A0;".date("j")."&#x00A0;".$mois[date("n")];
        }
        return $s;
    }

    /**
     * Cette fonction permet la sauvegarde de recherche dans un fichier csv 
     * @param (string) ùbookTitle qui le titre du livre que l'on a recherché
     */
    function enregistrerTitre(string $titre) {
        $nomFichier = "book_search.csv";
        
        $fichierExiste = file_exists($nomFichier);
        
        if($fichierExiste){
            $fichier = fopen($nomFichier, "r+");
            $ligne = fgetcsv($fichier);
            if (!($ligne[1] == date("j"))) {
                fclose($fichier);
                unlink($nomFichier);
                enregistrerTitre($titre);
                return false;
            }
        }
        else{
            $fichier = fopen($nomFichier, "x+");
        }

        $nouvellesLignes = array();
        $nouvellesLignes[] = array("date", date("j"));
        $livreExiste = false;

        while (($ligne = fgetcsv($fichier)) !== false) {
            if ($ligne[0] == $titre) {
                $ligne[1]++;
                $livreExiste = true;
            }
            $nouvellesLignes[] = $ligne;
        }

        if (!$livreExiste) {
            $nouvellesLignes[] = array($titre, 1);
        }
    
        rewind($fichier);
        foreach ($nouvellesLignes as $ligne) {
            fputcsv($fichier, $ligne);
        }

        fclose($fichier);
    }

    /**
     * Cette fonction permet de choisir aléatoirement une image dans un dossier
     * @param (string) $dirPath le dossier dans lequel on veut choisir l'image aléatoirement
     * @return (string) renvoie le chemin pour l'image choisi
     */
    function getRandomImage(string $dirPath):string {
        $files = scandir($dirPath);
    
        $files = array_filter($files, function($file) {
            return !in_array($file, [".", "..", ".DS_Store"]);
        });
    
        $randomIndex = array_rand($files);
        $randomFile = $files[$randomIndex];
    
        return $dirPath . "/" . $randomFile;
    }

    /**
     * Cette fonction permet le changement de page
     * @param (string) $rech la recherche que l'on effectue
     * @param (int) $num le nombre de livre qu'on veut par page
     * @param (string) $p la page sur laquelle on lance la fonction
     * $param (int) $page le numero de page
     * @return (string) $s le code html genere
     */
    function changPage(string $rech, int $num, string $p,int $page = 1):string{
        
        $s = "<article class=\"pagination\">\n";
        $pprec = $page-1;
        $psuiv = $page+1;
        if($page>=2){
            $s.="\t<a class=\"précédant\"href=\"./".$p."?search=".urlencode($rech)."&amp;page=".$pprec."\">Page Precedente</a>\n";
        }
        else{
            $s.="\t<a class=\"précédant\" href=\"./".$p."?search=".urlencode($rech)."&amp;page=".$page."\">Page Precedente</a>\n";
        }
        
        $s.="\t<p>".$page."</p>\n";

        if($num>(20*$page)){
            $s.="\t<a class=\"suivant\" href=\"./".$p."?search=".urlencode($rech)."&amp;page=".$psuiv."\">Page Suivante</a>\n";   
        }
        else{
            $s.="\t<a class=\"suivant\" href=\"./".$p."?search=".urlencode($rech)."&amp;page=".$page."\">Page Suivante</a>\n";
        }
        $s.="</article>\n";

        return $s;
    }

    /**
     * Cette fonction permet l'affichage des résultats pour la page category et la page recherche
     * @param (array) $res contenant les resultats de la recherche dans l'api de google
     * @param (string) $p la page sur laquelle la fonction se lance
     * @return (string) $s le code html fournit par la fonction
     */
    function search_book(array $res, string $p): string {
        $s = "";
        if (!array_key_exists("items",$res)) {
            $s .= "<article>\n";
            $s .= "\t<p>Aucun livre trouvé.</p>\n";
            $s .= "</article>\n";
        } else {
            for ($i = 0; $i < count($res["items"]); $i++) {
                $s .= "<article>\n";
    
                if (isset($res["items"][strval($i)]["volumeInfo"]["title"])) {
                    $title = $res["items"][strval($i)]["volumeInfo"]["title"];
                    $title = htmlspecialchars($title, ENT_QUOTES, "UTF-8");
                    $s .= "\t<h3>".$title."</h3>\n";
                }
    
                if (isset($res["items"][strval($i)]["volumeInfo"]["imageLinks"]["thumbnail"])) {
                    $img = $res["items"][strval($i)]["volumeInfo"]["imageLinks"]["thumbnail"];
                    $s .= "\t<img src=\"" . htmlspecialchars($img, ENT_QUOTES, 'UTF-8') . "\" alt=\"Première de couverture\"/>\n";
                } else {
                    $s .= "\t<img style=\"width: 11%; height: 16%;\" src=\"./ressource/images.jpg\" alt=\"Première de couverture\"/>\n";
                }
    
                if (isset($res["items"][strval($i)]["volumeInfo"]["authors"])) {
                    $authors = implode(", ", $res["items"][strval($i)]['volumeInfo']['authors']);
                    $s .= "\t<p>By " . htmlspecialchars($authors, ENT_QUOTES, 'UTF-8') . "</p>\n";
                }
    
                if (isset($res["items"][strval($i)]["volumeInfo"]["description"])) {
                    $des = $res["items"][strval($i)]["volumeInfo"]["description"];
                    $des = str_replace("&", "&amp;", strtolower($des));
                    $des = str_replace("<p>", "", strtolower($des));
                    $des = str_replace("</p>", "", strtolower($des));
                    $des = str_replace("<b>", "", strtolower($des));
                    $des = str_replace("</b>", "", strtolower($des));
                    $des = str_replace("<br>", "", strtolower($des));
                    $des = str_replace("</br>", "", strtolower($des));
                    $des = str_replace("<i>", "", strtolower($des));
                    $des = str_replace("</i>", "", strtolower($des));
                    $s .= "\t<p>" . htmlspecialchars($des, ENT_QUOTES, 'UTF-8') . "</p>\n";
                }
    
                if (isset($_GET["page"])) {
                    if (isset($_GET["search"])) {
                        $search = transfoSpace($_GET["search"]);
                        $s .= "\t<p class=\"plus\"><a href=\"./plus.php?search=" . urlencode($title) . "&amp;lastsearch=" . urlencode($search) . "&amp;lastpage=" . urlencode($p) . "\">Plus</a></p>\n";
                    } else if (isset($_GET["category"])) {
                        $search = transfoSpace($_GET["category"]);
                        $s .= "\t<p class=\"plus\"><a href=\"./plus.php?search=" . urlencode($title) . "&amp;lastsearch=" . urlencode($search) . "&amp;lastpage=" . urlencode($p) . "\">Plus</a></p>\n";
                    } else {
                        $s .= "\t<p class=\"plus\"><a href=\"./plus.php?search=" . urlencode($title) . "&amp;lastpage=" . urlencode($p) . "\">Plus</a></p>\n";
                    }
                } else {
                    if (isset($_GET["search"])) {
                        $search = transfoSpace($_GET["search"]);
                        $s .= "\t<p class=\"plus\"><a href=\"./plus.php?search=" . urlencode($title) . "&amp;lastsearch=" . urlencode($search) . "&amp;lastpage=" . urlencode($p)."\">Plus</a></p>\n";
                    }
                    else if(isset($_GET["category"])){
                        $s.="\t<p class=\"plus\"><a href=\"./plus.php?search=".urlencode($title)."&amp;lastsearch=" . urlencode($_GET["category"])."&amp;lastpage=".urlencode($p)."\">Plus</a></p>\n";
                    }
                    else{
                        $s.="\t<p class=\"plus\"><a href=\"./plus.php?search=".urlencode($title)."&amp;lastpage=".urlencode($p)."\">Plus</a></p>\n";
                    }
                }
                $s.="</article>\n";
            }
        }
        return $s;
    }

    /**
     * Cette fonction permet l'affichage en details d'un livre
     * @param (string) $book_title le titre du livre qu'on veut voir en details
     * @param (string) $rech la derniere recherche effectuer avant de voir le livre en details
     * @param (string) $page la page sur laquelle on etait avant de lancer la fonction
     */
    function displayBookDetails(string $book_title, string $rech, string $page) {
        $search = urlencode($book_title);
        $url = "https://www.googleapis.com/books/v1/volumes?q={$search}";
    
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        echo "<article>\n";
    
        if ($data["totalItems"] == 0) {
            echo "\t<p>Aucun livre trouvé.</p>\n";
        } else {
            $book_id = $data["items"][0]["id"];
            $url = "https://www.googleapis.com/books/v1/volumes/{$book_id}";
            $response = file_get_contents($url);
            $data = json_decode($response, true);
    
            if (isset($data["error"])) {
                echo "\t<p>Une erreur est survenue : {$data["error"]["message"]}</p>\n";
            } else {
                $title = $data["volumeInfo"]["title"];
                $title = htmlspecialchars($title, ENT_QUOTES, "UTF-8");
                echo "\t<h3>".$title."</h3>\n";
                if (isset($data["volumeInfo"]["imageLinks"]["thumbnail"])) {
                    $img = $data["volumeInfo"]["imageLinks"]["thumbnail"];
                    echo "\t<img src=\"".htmlspecialchars($img, ENT_QUOTES, 'UTF-8')."\" alt=\"Première de couverture\"/>\n";
                } else {
                    echo "\t<img style=\"width:20%;height:20%;\" src=\"./ressource/images.jpg\" alt=\"Première de couverture\"/>\n";
                }
                if (isset($data["volumeInfo"]["authors"])) {
                    echo "\t<p>Par " . implode(", ", $data["volumeInfo"]["authors"]) . "</p>\n";
                }
                if (isset($data["volumeInfo"]["description"])) {
                    $des = $data["volumeInfo"]["description"];
                    $des = str_replace("&", "&amp;", strtolower($des));
                    $des = str_replace("<p>", "", strtolower($des));
                    $des = str_replace("</p>", "", strtolower($des));
                    $des = str_replace("<b>", "", strtolower($des));
                    $des = str_replace("</b>", "", strtolower($des));
                    $des = str_replace("<br>", "", strtolower($des));
                    $des = str_replace("</br>", "", strtolower($des));
                    $des = str_replace("<i>", "", strtolower($des));
                    $des = str_replace("</i>", "", strtolower($des));
                    echo "\t<p>".htmlspecialchars($des, ENT_QUOTES, 'UTF-8')."</p>\n";
                }
                if (isset($data["volumeInfo"]["categories"])) {
                    $cat = implode(", ", $data["volumeInfo"]["categories"]);
                    $cat = htmlspecialchars($cat, ENT_QUOTES, 'UTF-8');
                    echo "\t<p>Catégorie : " . $cat . "</p>\n";
                }
                if (isset($data["volumeInfo"]["publisher"])) {
                    echo "\t<p>Éditeur : {$data["volumeInfo"]["publisher"]}</p>\n";
                }
                if (isset($data["volumeInfo"]["publishedDate"])) {
                    echo "\t<p>Date de publication : {$data["volumeInfo"]["publishedDate"]}</p>\n";
                }
                if (isset($data["volumeInfo"]["pageCount"])) {
                    echo "\t<p>Nombre de pages : {$data["volumeInfo"]["pageCount"]}</p>\n";
                }
                if (isset($data["volumeInfo"]["language"])) {
                    echo "\t<p>Langue : {$data["volumeInfo"]["language"]}</p>\n";
                }
                if (isset($data["volumeInfo"]["industryIdentifiers"]["0"]["identifier"])) {
                    echo "\t<p> ISBN : {$data["volumeInfo"]["industryIdentifiers"]["0"]["identifier"]}</p>\n";
                }
                if (isset($data["volumeInfo"]["previewLink"])) {
                    $lienD = $data["volumeInfo"]["previewLink"];
                    $lienD = htmlspecialchars($lienD, ENT_QUOTES, "UTF-8");
                    echo "\t<p><a href=\"".$lienD."\" target=\"_blank\">Lien vers l'aperçu du livre</a></p>\n";
                }
                if(isset($data["saleInfo"]["buyLink"])){
                    $lienS = $data["saleInfo"]["buyLink"];
                    $lienS = htmlspecialchars($lienS, ENT_QUOTES, "UTF-8");
                    echo "\t<p><a href=\"".$lienS."\" target=\"_blank\">Lien pour acheter le livre</a></p>\n";
                }
                $rech = transfoSpace($rech);
                if($page=="category.php"){
                    echo "<p class=\"pre\"><a href=\"./".urlencode($page)."?category=".transfoSpace($rech)."\">Page Precedente</a></p>";
                }
                else{
                    echo "<p class=\"pre\"><a  href=\"./".urlencode($page)."?search=".transfoSpace($rech)."\">Page Precedente</a></p>";
                }
            }
            echo "</article>\n";
        }
    }

    /**
     * Cette fonction permet la transformation de caractere speciaux en +
     * @param (string) $title le titre d'un livre
     * @return (string) $title le titre apres changement
     */
     function transfoSpace (string $title):string{
        $title = (str_replace(" ", "+", strtolower($title)));
        $title = (str_replace("\"", "+", strtolower($title)));
        $title = (str_replace("[", "+", strtolower($title)));
        $title = (str_replace("]", "+", strtolower($title)));
        return $title;
    }

    /**
     * Cette fonction permet via l'api du New York times de recuperer les best seller du moment
     */
    function getBestSellers() {
        $file = "bestseller.csv";
        $time_last_modified = file_exists($file) ? filemtime($file) : 0;
        $time_now = time();
    
        
        if ($time_now - $time_last_modified > 604800) {
            $url = "https://api.nytimes.com/svc/books/v3/lists/overview.json?api-key=YBGFdKnZaD9hj4FtrJZNOqiRASBUsLtg";
            $response = file_get_contents($url);
            $data = json_decode($response, true);
            $lists = $data["results"]["lists"];
            $file = fopen($file, "w") or die("Impossible d'ouvrir le fichier !");
            $count = 0;
            foreach ($lists as $list) {
                $books = $list["books"];
                foreach ($books as $book) {
                    $title = $book["title"];
                    fwrite($file, $title . "\n");
                    $count++;
                    if ($count == 5) {
                        break 2;
                    }
                }
            }
            fclose($file);
        }
        
        affBestSeller();
    }
    
      
    /**
     * Cette fonction permet l'affichage du dernier livre recherche
     * @param (string) $title le titre du livre
     */
    function affLastSearch(string $title){
        $search = urlencode($title);
        $url = "https://www.googleapis.com/books/v1/volumes?q={$search}";
    
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        echo "<article>\n";
    
        if ($data["totalItems"] == 0) {
            echo "\t<p>Aucun livre trouvé.</p>\n";
        }
        else {
            $book_id = $data["items"][0]["id"];
            $url = "https://www.googleapis.com/books/v1/volumes/{$book_id}";
            $response = file_get_contents($url);
            $data = json_decode($response, true);
    
            if (isset($data["error"])) {
                echo "\t<p>Une erreur est survenue : {$data["error"]["message"]}</p>\n";
            }
            else {
                $title = $data["volumeInfo"]["title"];
                $title = htmlspecialchars($title, ENT_QUOTES, "UTF-8");
                echo "\t<h3>".$title."</h3>\n";
                if (isset($data["volumeInfo"]["imageLinks"]["thumbnail"])) {
                    $img = $data["volumeInfo"]["imageLinks"]["thumbnail"];
                    $img = htmlspecialchars($img, ENT_QUOTES, "UTF-8");
                    echo "\t<img src=\"".$img."\" alt=\"Première de couverture\"/>\n";
                }
                else{
                    echo "\t<img style=\"width:128px;height:197px;\" src=\"./ressource/images.jpg\" alt=\"Première de couverture\"/>\n";
                }
                if (isset($data["volumeInfo"]["authors"])) {
                    echo "\t<p>Par " . implode(", ", $data['volumeInfo']['authors']) . "</p>\n";
                }
                if (isset($data["volumeInfo"]["description"])) {
                    $des = $data["volumeInfo"]["description"];
                    $des = str_replace("&", "&amp;", strtolower($des));
                    $des = str_replace("<p>", "", strtolower($des));
                    $des = str_replace("</p>", "", strtolower($des));
                    $des = str_replace("<b>", "", strtolower($des));
                    $des = str_replace("</b>", "", strtolower($des));
                    $des = str_replace("<br>", "", strtolower($des));
                    $des = str_replace("</br>", "", strtolower($des));
                    $des = str_replace("<i>", "", strtolower($des));
                    $des = str_replace("</i>", "", strtolower($des));
                    $des = htmlspecialchars($des, ENT_QUOTES, 'UTF-8');
                    echo "\t<p>".$des."</p>\n";
                }
                echo "\t<p class=\"plus\"><a href=\"./plus.php?search=".transfoSpace($title)."&amp;lastpage=index.php\">Plus</a></p>\n";
            }
            echo "</article>\n";
        }
    }

    /**
     * Cette fonction permet l'affichage des best sellers
     * @param (string) $title le titre du livre
     */
    function affBestSeller() {
        $file = fopen("bestseller.csv", "r");
        
        while (($title = fgetcsv($file)) !== false) {
            $search = urlencode($title[0]);
            $url = "https://www.googleapis.com/books/v1/volumes?q={$search}";
            $response = file_get_contents($url);
            if($response == false){
                echo "<article>";
                echo "<p>Aucun livre trouvé</p>";
                echo "</article>";
            }else{
            $data = json_decode($response, true);
            echo "<article class=\"bestsell\">\n";
    
            if ($data["totalItems"] == 0) {
                echo "\t<p>Aucun livre trouvé pour le titre \"{$title[0]}\".</p>\n";
            }
            else {
                    $book_id = $data["items"][0]["id"];
                    $url = "https://www.googleapis.com/books/v1/volumes/{$book_id}";
                    $response = file_get_contents($url);
                    $data = json_decode($response, true);
        
                    if (isset($data["error"])) {
                        echo "\t<p>Une erreur est survenue : {$data["error"]["message"]}</p>\n";
                    }
                    else {
                        echo "\t<p class=\"plus\"><a href=\"./plus.php?search=".transfoSpace($title[0])."&amp;lastpage=index.php\">Plus</a></p>\n";
                        if (isset($data["volumeInfo"]["imageLinks"]["thumbnail"])) {
                            $img = $data["volumeInfo"]["imageLinks"]["thumbnail"];
                            $img = htmlspecialchars($img, ENT_QUOTES, "UTF-8");
                            echo "\t<img src=\"".$img."\" alt=\"Première de couverture\"/>\n";
                        }
                        else{
                            echo "\t<img style=\"width:128px;height:197px;\" src=\"./ressource/images.jpg\" alt=\"Première de couverture\"/>\n";
                        }
                        $title = $data["volumeInfo"]["title"];
                        $title = htmlspecialchars($title, ENT_QUOTES, "UTF-8");
                        echo "\t<h3>".$title."</h3>\n";
                        if (isset($data["volumeInfo"]["authors"])) {
                            echo "\t<p>Par " . implode(", ", $data['volumeInfo']['authors']) . "</p>\n";
                        }
                    }
                    echo "</article>\n";
                }
            }
        }
    
        fclose($file);
    }
    
    /**
     * Cette fonction permet l'affichage d'un diagramme pour le nombre de fois qu'un livre est recherche
     * @param (string) $filename le nom du fichier
     */
    function create_bar_chart_from_csv(string $filename) {
        if (file_exists($filename)) {
            $file = fopen($filename, 'r');
        
            $headers = fgetcsv($file);
            
            $labels = array();
            $values = array();
            
            while (($row = fgetcsv($file)) !== FALSE) {
                $labels[] = htmlspecialchars($row[0]);
                
                $values[] = htmlspecialchars($row[1]);
            }
            
            fclose($file);
            
            echo '<div style="width: 100%; margin: 0 auto;">';
            echo '<canvas id="myChart"></canvas>';
            echo '</div>';
            echo '<script>';
            echo 'var ctx = document.getElementById("myChart").getContext("2d");';
            echo 'var myChart = new Chart(ctx, {';
            echo 'type: "bar",';
            echo 'data: {';
            echo 'labels: ' . json_encode($labels) . ',';
            echo 'datasets: [{';
            echo 'label: "Nombre de recherche",';
            echo 'data: ' . json_encode($values) . ',';
            echo 'backgroundColor: "rgba(54, 162, 235, 0.2)",';
            echo 'borderColor: "rgba(54, 162, 235, 1)",';
            echo 'borderWidth: 1';
            echo '}]';
            echo '},';
            echo 'options: {';
            echo 'scales: {';
            echo 'yAxes: [{';
            echo 'ticks: {';
            echo 'beginAtZero: true';
            echo '}';
            echo '}]';
            echo '}';
            echo '}';
            echo '});';
            echo '</script>';
        } else {
            echo "<article>";
            echo "<p>Le fichier $filename n'existe pas.</p>";
            echo "</article>";
        }
    }
?>