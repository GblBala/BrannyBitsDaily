        <footer>
            <div>
                <?php
                    echo(dateAff($_SERVER['HTTP_ACCEPT_LANGUAGE'][0].$_SERVER['HTTP_ACCEPT_LANGUAGE'][1]));
                ?>
            </div>
            <p> Site créé par Lauriane BLANCHENET, Boyan CHEYNET et Bala GOBALOUKICHENIN</p>
            <div>
                <span><a href="./tech.php">Page tech</a></span>
            </div>
            <?php
                $filename = "compteur.txt";
                $filetime = file_exists($filename) ? filemtime($filename) : 0;
                $currenttime = time();
                $elapsedtime = $currenttime - $filetime;

                if ($elapsedtime > 86400) { // 86400 secondes = 24 heures
                    $compteur = 1;
                    file_put_contents($filename, $compteur, LOCK_EX);
                } else {
                    $compteur = file_exists($filename) ? file_get_contents($filename) + 1 : 1;
                    file_put_contents($filename, $compteur, LOCK_EX);
                }

                echo $compteur;
            ?>
        </footer>
    </body>
</html>
