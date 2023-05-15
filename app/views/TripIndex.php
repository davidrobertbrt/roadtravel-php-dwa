<html>
    <head>
        <?php require_once('../app/components/header.php');?>
    </head>
    <body>
        <?php require_once('../app/components/navbar.php');?>
        <div class = "container mt-4"> 
                    <h2>Create a new trip</h2>
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
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                            <form action="delete" method="POST" class="inline-form">
                                <input type="hidden" name="id" value="">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                            <form action="edit" method="POST" class="inline-form">
                                <input type="hidden" name="id" value="">
                                <!-- Include other fields for editing -->
                                <button type="submit" class="btn btn-primary">Edit</button>
                            </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
        </div>
        <?php require_once('../app/components/footer.php');?>
    </body>
</html>
