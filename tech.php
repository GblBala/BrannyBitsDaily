<?php
    declare(strict_types=1);
    $page="tech.php";
    $titre = "Tech";
    include "./include/h_and_f/header.inc.php";
    include "./include/fonction.inc.php";
?>
        <main>
            <section>
                <h2>La découverte des API</h2>
                <article>
                    <?php
                        $imgSat = extractionApiJSON("https://api.nasa.gov/planetary/apod?api_key=r43ezMNa8zZWiQLJDaPBqvCkyWe71ue1lOgD3DoJ&date=".date("Y")."-".date("m")."-".strval(intval(date("d")-1)));
                        
                        $s = "";
                        if($imgSat["media_type"] == "image"){
                            $s.= "<h3>Image du jour de la NASA</h3>\n";
                            $s.= "<figure>\n";
                            $s.= "\t<img style=\"height : 30%;width:30%;\" alt=\"Image du jour\" src=\"".$imgSat["url"]."\" decoding=\"async\"/>\n";
                            $s.= "\t<figcaption>".$imgSat["title"]."</figcaption>\n";
                            $s.="</figure>\n";
                        }
                        else if($imgSat["media_type"] == "video"){
                            $s.= "<h3>".$imgSat["title"]."</h3>\n";
                            $s.= "\t<iframe style=\"height : 400px; width: 550px;\" src=\"".$imgSat["url"]."\"></iframe>\n";
                        }
                        echo $s;
                    ?>
                </article>
                <article>
                    <h3>Localisation via GeoPlugin</h3>
                    <?php
                        $parse = extractionApiXML("http://www.geoplugin.net/xml.gp?ip=".$_SERVER["REMOTE_ADDR"]);
                        echo "<p>Vous etes positionné à ".$parse->geoplugin_city.", ".$parse->geoplugin_regionName.", ".$parse->geoplugin_region.", ".$parse->geoplugin_countryName."</p>\n";
                    ?>
                </article>
                <article>
                    <h3>Localisation via IpInfo.io</h3>
                    <?php
                        $loca = extractionApiJSON("https://ipinfo.io/".$_SERVER["REMOTE_ADDR"]."/geo");
                        echo "<p>Vous etes positionné à ".$loca["city"].", ".$loca["region"]."</p>\n"
                    ?>
                </article>
                <article>
                    <h3>Localisation via whatismyip</h3>
                    <?php
                        $lieu = extractionApiXML("https://api.whatismyip.com/ip-address-lookup.php?key=600d7ef44c92cb9b87ef52c62462750d&input=".$_SERVER["REMOTE_ADDR"]."&output=xml");
                        echo "<p>Vous etes positionné à ".$lieu->server_data->city.", ".$lieu->server_data->postalcode.", ".$lieu->server_data->region.", ".$lieu->server_data->country."</p>\n";
                    ?>
                </article>
            </section>
        </main>
<?php
    include "./include/h_and_f/footer.inc.php";
?>