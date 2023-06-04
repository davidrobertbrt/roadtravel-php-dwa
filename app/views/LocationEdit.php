<html>
    <head>
        <?php require_once('../app/components/header.php');?>
    </head>
    <body>
        <?php require_once('../app/components/navbar.php');?>
        <div class = "container mt-4">
        <?php
            $crLocation = $data['crLocation'];
            if(!empty($crLocation)) {
        ?>
                <form method="POST" action="process">
                        <?php
                            $id = $crLocation->getId();
                            $name = $crLocation->getName();
                            $latitude = $crLocation->getLatitude();
                            $longitude = $crLocation->getLongitude();
                        ?>
                        <div class="form-group">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <label for="name">Nume:</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Introdu numele orașului" value="<?php echo $name; ?>">
                        </div>
                        <div class="form-group">
                            <p>Datele sunt preluate folosind OpenMeteo API <a href="https://open-meteo.com/en/docs/geocoding-api" target="_blank">Află mai multe...</a></p>
                            <p>Dacă nu se scriu valori pentru longitudine și latitudine se va folosi API-ul open meteo pentru a le afla.</p>
                        </div>
                        
                        <div class="form-group">
                            <label for="longitude">Longitudine:</label>
                            <input type="text" class="form-control" id="longitude" placeholder="Introdu longitudine" name="longitude" value="<?php echo $longitude; ?>">
                        </div>
                        <div class="form-group">
                            <label for="latitude">Latitudine:</label>
                            <input type="text" class="form-control" id="latitude" placeholder="Introdu latitudinea" name="latitude" value="<?php echo $latitude; ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
            <?php 
                }
            ?>
        </div>
        <?php require_once('../app/components/footer.php');?>
    </body>
</html>
