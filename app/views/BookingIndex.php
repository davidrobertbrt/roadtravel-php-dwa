<html>
    <head>
        <?php require_once('../app/components/header.php');?>
    </head>
    <body>
        <?php require_once('../app/components/navbar.php');?>
        <div class = "container mt-4">
        <form id="ticket-form" method="POST" action="process">
            <div class="form-group">
                <label for="location">Plecare:</label>
                <select class="form-control" id="location" name="location" required>
                    <option value="">Selectează o locație</option>
                <?php
                    foreach($data['locationRepo'] as $location)
                    {
                        $id = $location->getId();
                        $name = $location->getName();
                ?>
                    <option value="<?php echo $id; ?>"><?php echo $name;?></option>
                <?php
                    }
                ?>
                </select>
            </div>
            <div class="form-group">
                <label for="date">Data plecării:</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="time">Ora plecării:</label>
                <input type="time" class="form-control" id="time" name="time" required>
            </div>
            <div class="form-group">
                <label for="trips">Listă rute:</label>
                <select class="form-control" id="trips" name="trips" required>
                    <option value="">Selectează o locație și o dată mai întâi</option>
                </select>
            </div>
            <div class="form-group">
                <label for="persons">Număr persoane:</label>
                <input type="number" class="form-control" id="persons" name="persons" min="1" max="3" required>
            </div>
            <div class="form-group">
                <label for="discount">Cod de reducere:</label>
                <input type="text" class="form-control" id="discount" name="discount">
            </div>
            <button type="submit" class="btn btn-primary">Planifică</button>
        </form>
    </div>
    <?php require_once('../app/components/footer.php');?>
    <script>
        $(document).ready(function() {
            // Update trips select dropdown when the location or date or time changes
            $('#location, #date, #time').change(function() {
                var location = $('#location').val();
                var date = $('#date').val();
                var time = $('#time').val();

                if (location && date && time) {
                    $.ajax({
                        type: 'POST',
                        url: 'fetchAvailableTrips', // URL of the PHP script for fetching trips
                        data: {
                            location: location,
                            date: date,
                            time: time
                        },
                        success: function(response) {
                            $('#trips').html(response); // Update the trips select dropdown with the response
                        }
                    });
                } else {
                    $('#trips').html('<option value="">Selectează o locație și o dată mai întâi</option>'); // Reset the trips select dropdown
                }
            });
        });
    </script>
    </body>
</html>
