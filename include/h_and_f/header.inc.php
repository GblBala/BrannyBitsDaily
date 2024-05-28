<?php
    if (!isset($_COOKIE["style"])) {
        setcookie("style", "jour", time() + (30 * 24 * 60 * 60));
    }

    if(isset($_GET["style"])){
        if($_GET["style"]=="jour"){
            setcookie("style", "jour", time() + (30 * 24 * 60 * 60));
        }
        else{
            setcookie("style", "nuit", time() + (30 * 24 * 60 * 60));
        }
    }

    if(isset($_GET["style"])){
        if($_GET["style"]=="jour"){
            $css = "./css/style.css";
        }
        else{
            $css = "./css/alt.css";
        }
    }
    else if(isset($_COOKIE["style"])){
        if($_COOKIE["style"]=="nuit"){
            $css = "./css/alt.css";
        }
        else if($_COOKIE["style"]=="jour"){
            $css = "./css/style.css";
        } 
    }
    else{
        $css = "./css/style.css";
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <link rel="icon" href="./images/Lithub.png"/>
       <?php echo" <link href=\"".$css."\" rel=\"stylesheet\"/>" ?>
		<meta charset="utf-8"/>
        <meta name="description" content="Projet de developpement web"/>
        <meta name="author" content="Cheynet Boyan et Blanchenet Lauriane"/>
        <meta name="date" content="20/02/2023"/>
        <title> <?= $titre; ?> </title>
    </head>
    <body>
        
        <div>
            <figure id="acc">
                <a href="index.php"><img src="data:image/png;base64,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" alt="icon"/></a>
            </figure>
            <ul class = "menu" style="list-style: none;">
                <?php
                    $sty = "?";
                    $str = "";
                    if(isset($_GET["style"])){
                        if($_GET["style"]=="jour"){
                            $sty.="style=nuit";
                        }
                        else{
                            $sty.="style=jour";
                        }
                    }
                    else if(isset($_COOKIE["style"])){
                        if($_COOKIE["style"]=="jour"){
                            $sty.="style=nuit";
                        }
                        else{
                            $sty.="style=jour";
                        }
                    }
                    else{
                        $sty.="style=nuit";
                    }

                    foreach($_GET as $key => $value){
                        if($key!="style"){
                            $str.="&".$key."=".$value;
                        }
                    }


                    if(isset($_GET["style"])){
                        if($_GET["style"]=="jour"){
                            echo "<li><a href=\"./".$page.$sty.htmlspecialchars(urlencode($str), ENT_QUOTES, "UTF-8")."\"><img src=\"./ressource/images/dark.png\" alt=\"mode nuit\"/></a></li>\n";
                        }
                        else{
                            echo "<li><a href=\"./".$page.$sty.htmlspecialchars(urlencode($str), ENT_QUOTES, "UTF-8")."\"><img src=\"./ressource/images/dark.png\" alt=\"mode jour\"/></a></li>\n";
                        }
                    }
                    else if(isset($_COOKIE["style"])){
                        if($_COOKIE["style"]=="jour"){
                            echo "<li><a href=\"./".$page.$sty.htmlspecialchars(urlencode($str), ENT_QUOTES, "UTF-8")."\"><img src=\"./ressource/images/dark.png\" alt=\"mode nuit\"/></a></li>\n";
                        }
                        else{
                            echo "<li><a href=\"./".$page.$sty.htmlspecialchars(urlencode($str), ENT_QUOTES, "UTF-8")."\"><img src=\"./ressource/images/dark.png\" alt=\"mode jour\"/></a></li>\n";
                        }
                    }
                    else{
                        echo "<li><a href=\"./".$page.$sty.htmlspecialchars(urlencode($str), ENT_QUOTES, "UTF-8")."\"><img  src=\"./ressource/images/dark.png\" alt=\"mode nuit\"/></a></li>\n";
                    }
                ?>
            </ul>
        </div>
       
        <h1><?= $titre; ?></h1>
        <header>
            <nav>
                <ul>
                    <li class= "dec"><a href="decouvert.php">Recherche</a></li>
                    <li class="stat"><a href="statistique.php">Statistiques</a></li>
                   
                </ul>
            </nav>
        </header>
        