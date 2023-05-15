<html>
    <head>
        <?php require_once('../app/components/header.php');?>
    </head>
    <body>
        <?php require_once('../app/components/navbar.php');?>
        <div class = "container mt-4">
        <h2>Edit the trip</h2>
        <p>Warning! This action will notify all the users who booked this trip that it has been modified!</p>
        <p>Setting the trip in the past will make it unavailable.</p>
                <form>
                    <div class="form-group">
                        <label for="departureSelect">Bus select:</label>
                        <select class="form-control" id="busSelect">
                        <option>Bus 1</option>
                        <option>Bus 2</option>
                        <option>Bus 3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="departureSelect">Departure:</label>
                        <select class="form-control" id="departureSelect">
                        <option>Departure 1</option>
                        <option>Departure 2</option>
                        <option>Departure 3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="arrivalSelect">Arrival:</label>
                        <select class="form-control" id="arrivalSelect">
                        <option>Arrival 1</option>
                        <option>Arrival 2</option>
                        <option>Arrival 3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dateTimeDeparture">Date/Time of Departure:</label>
                        <input type="datetime-local" class="form-control" id="dateTimeDeparture">
                    </div>
                    <div class="form-group">
                        <label for="dateTimeArrival">Date/Time of Arrival:</label>
                        <input type="datetime-local" class="form-control" id="dateTimeArrival">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
        </div>
        <?php require_once('../app/components/footer.php');?>
    </body>
</html>
