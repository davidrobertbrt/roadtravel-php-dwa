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
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="<?php echo $name; ?>">
                        </div>
                        <div class="form-group">
                            <p>Data will be fetched using Geolocation API from Open-Meteo <a href="https://open-meteo.com/en/docs/geocoding-api" target="_blank">Learn more...</a></p>
                            <p>If you want to add yourself the longitude and latitude, you can do so below.</p>
                        </div>
                        
                        <div class="form-group">
                            <label for="longitude">Longitude:</label>
                            <input type="text" class="form-control" id="longitude" placeholder="Enter Longitude" name="longitude" value="<?php echo $longitude; ?>">
                        </div>
                        <div class="form-group">
                            <label for="latitude">Latitude:</label>
                            <input type="text" class="form-control" id="latitude" placeholder="Enter Latitude" name="latitude" value="<?php echo $latitude; ?>">
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
