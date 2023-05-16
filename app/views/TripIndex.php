<html>
    <head>
        <?php require_once('../app/components/header.php');?>
    </head>
    <body>
        <?php require_once('../app/components/navbar.php');?>
        <div class = "container mt-4"> 
                    <h2>Create a new trip</h2>
                    <form method = "POST" action="create">
                    <div class="form-group">
                        <label for="departureSelect">Bus select:</label>
                        <select class="form-control" id="busSelect" name = "busId">
                        <!-- import from bus repository.-->
                        <?php
                            foreach($data['busRepo'] as $bus) {
                                $id = $bus->getId();
                                $nrSeats = $bus->getNrSeats();
                                $optionUrl = "(Bus {$id} | {$nrSeats})";
                        ?>
                            <option value = "<?php echo $id;?>"><?php echo $optionUrl; ?></option>
                        <?php }?>
                        </select>
                    </div>
                    <!-- import from location repository -->
                    <div class="form-group">
                        <label for="departureSelect">Departure:</label>
                        <select class="form-control" id="departureSelect" name = "departureId">
                            <?php
                                foreach($data['locationRepo'] as $location) {
                                    $id = $location->getId();
                                    $name = $location->getName();
                            ?>
                                <option value = "<?php echo $id;?>"><?php echo $name; ?></option>
                            <?php }?> 
                        </select>
                    </div>
                    <div class="form-group">
                        <!-- import from location repository -->
                        <label for="arrivalSelect">Arrival:</label>
                        <select class="form-control" id="arrivalSelect" name = "arrivalId">
                            <?php
                                foreach($data['locationRepo'] as $location) {
                                    $id = $location->getId();
                                    $name = $location->getName();
                            ?>
                                <option value = "<?php echo $id;?>"><?php echo $name; ?></option>
                            <?php }?> 
                        </select>
                    </div>
                    <div class="form-group">
                        <!-- no import -->
                        <label for="dateTimeDeparture">Date/Time of Departure:</label>
                        <input type="datetime-local" class="form-control" id="dateTimeDeparture" name = "dateTimeStart">
                    </div>
                    <div class="form-group">
                        <label for="dateTimeArrival">Date/Time of Arrival:</label>
                        <input type="datetime-local" class="form-control" id="dateTimeArrival" name="dateTimeEnd">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                <h2>Schedule</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Bus</th>
                            <th>Departure</th>
                            <th>Arrival</th>
                            <th>Date Departure</th>
                            <th>Date Arrival</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <?php 
                        if(!empty($data['tripRepo'])) {
                    ?>
                    <tbody>
                        <?php
                            foreach ($data['tripRepo'] as $trip) {
                            $id = $trip->getId();
                            $busId = $trip->getBusId();
                            $departureId = $trip->getLocationStartId();
                            $arrivalId = $trip->getLocationEndId();
                            $dateTimeStart = $trip->getDateTimeStart();
                            $dateTimeEnd = $trip->getDateTimeEnd();
                        ?>
                        <tr>
                            <td><?php echo $id; ?></td>
                            <td><?php echo $busId; ?></td>
                            <td><?php echo $data['locationRepo'][$departureId]->getName(); ?></td>
                            <td><?php echo $data['locationRepo'][$arrivalId]->getName();?></td>
                            <td><?php echo $dateTimeStart; ?></td>
                            <td><?php echo $dateTimeEnd; ?></td>
                            <td>
                            <form action="delete" method="POST" class="inline-form">
                                <input type="hidden" name="id" value="<?php echo $id;?>">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                            <form action="edit" method="POST" class="inline-form">
                                <input type="hidden" name="id" value="<?php echo $id;?>">
                                <!-- Include other fields for editing -->
                                <button type="submit" class="btn btn-primary">Edit</button>
                            </form>
                            </td>
                        </tr>
                        <?php 
                            }
                        ?>
                    </tbody>
                <?php } ?>
                </table>
        </div>
        <?php require_once('../app/components/footer.php');?>
    </body>
</html>
