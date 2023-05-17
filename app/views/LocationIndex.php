<html>
    <head>
        <?php require_once('../app/components/header.php');?>
    </head>
    <body>
        <?php require_once('../app/components/navbar.php');?>
        <div class = "container mt-4">
        <div class="container">
            <h2>Create a new location</h2>
                    <form method="POST" action="create">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                        </div>
                        <div class="form-group">
                            <p>Data will be fetched using Geolocation API from Open-Meteo <a href="https://open-meteo.com/en/docs/geocoding-api" target="_blank">Learn more...</a></p>
                            <p>If you want to add yourself the longitude and latitude, you can do so below.</p>
                        </div>
                        <div class="form-group">
                            <label for="longitude">Longitude:</label>
                            <input type="text" class="form-control" id="longitude" placeholder="Enter Longitude" name="longitude">
                        </div>
                        <div class="form-group">
                            <label for="latitude">Latitude:</label>
                            <input type="text" class="form-control" id="latitude" placeholder="Enter Latitude" name="latitude">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                <h2>Table</h2>
                <table class="table">
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Longitude</th>
                        <th>Latitude</th>
                        <th>Actions</th>
                        </tr>
                    </thead>
                    <?php
                        if(!empty($data['locations'])) {
                    ?>
                    <tbody>
                        <?php
                            foreach ($data['locations'] as $location) {
                            $id = $location->getId();
                            $name = $location->getName();
                            $longitude = $location->getLongitude();
                            $latitude = $location->getLatitude();
                        ?>
                        <tr>
                            <td><?php echo $id; ?></td>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $longitude; ?></td>
                            <td><?php echo $latitude; ?></td>
                            <td>
                            <form action="delete" method="POST" class="inline-form">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                            <form action="edit" method="POST" class="inline-form">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <!-- Include other fields for editing -->
                                <button type="submit" class="btn btn-primary">Edit</button>
                            </form>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                <?php 
                    }
                ?>
                </table>


        </div>
        <?php require_once('../app/components/footer.php');?>   
    </body>
</html>
