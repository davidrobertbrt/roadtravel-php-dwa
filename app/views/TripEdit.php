<html>
    <head>
        <?php require_once('../app/components/header.php');?>
    </head>
    <body>
        <?php require_once('../app/components/navbar.php');?>
        <div class = "container mt-4">
        <h2>Editează o călatorie</h2>
        <p>Dacă setezi data de plecare mai devreme, aceasta va fi anulată!</p>
        <form method = "POST" action="process">
                    <div class="form-group">
                        <input type="hidden" name="id" value="<?php echo $data['crTrip']->getId(); ?>">
                        <label for="departureSelect">Bus select:</label>
                        <select class="form-control" id="busSelect" name = "busId">
                        <!-- import from bus repository.-->
                        <?php
                            foreach($data['busRepo'] as $bus) {
                                $id = $bus->getId();
                                $nrSeats = $bus->getNrSeats();
                                $optionUrl = "(Bus {$id} | {$nrSeats})";

                                if($data['crTrip']->getBusId() === $id){
                        ?>
                            <option value = "<?php echo $id;?>" selected><?php echo $optionUrl; ?></option>
                        <?php }else{?>
                            <option value = "<?php echo $id;?>"><?php echo $optionUrl; ?></option>
                        <?php }}?>
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
                                    if($data['crTrip']->getLocationStartId() === $id){
                            ?>
                                <option value = "<?php echo $id;?>" selected><?php echo $name; ?></option>
                            <?php
                                }else{
                            ?>
                                <option value = "<?php echo $id;?>"><?php echo $name; ?></option>
                            <?php }}?> 
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
                                    if($data['crTrip']->getLocationEndId() === $id){
                            ?>
                                <option value = "<?php echo $id;?>" selected><?php echo $name; ?></option>
                            <?php }else{?>
                                <option value = "<?php echo $id;?>"><?php echo $name; ?></option>
                            <?php }}?> 
                        </select>
                    </div>
                    <div class="form-group">
                        <!-- no import -->
                        <?php 
                            $dateStart = $data['crTrip']->getDateTimeStart();
                            $dateEnd = $data['crTrip']->getDateTimeEnd();
                            $formatStart = str_replace(' ', 'T', $dateStart);
                            $formatEnd = str_replace(' ', 'T', $dateEnd);
                        ?>
                        <label for="dateTimeDeparture">Date/Time of Departure:</label>
                        <input type="datetime-local" class="form-control" id="dateTimeDeparture" name="dateTimeStart" value="<?php echo $formatStart;?>">
                    </div>
                    <div class="form-group">
                        <label for="dateTimeArrival">Date/Time of Arrival:</label>
                        <input type="datetime-local" class="form-control" id="dateTimeArrival" name="dateTimeEnd" value="<?php echo $formatEnd;?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
        </div>
        <?php require_once('../app/components/footer.php');?>
    </body>
</html>
