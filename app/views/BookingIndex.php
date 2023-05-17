<html>
    <head>
        <?php require_once('../app/components/header.php');?>
    </head>
    <body>
        <?php require_once('../app/components/navbar.php');?>
        <div class = "container mt-4">
        <form id="ticket-form">
            <div class="form-group">
                <label for="location">Location:</label>
                <select class="form-control" id="location" name="location" required>
                    <option value="">Select a location</option>
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
                <label for="date">Date of Departure:</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="time">Time of Departure:</label>
                <input type="time" class="form-control" id="time" name="time" required>
            </div>
            <div class="form-group">
                <label for="trips">List of Trips:</label>
                <select class="form-control" id="trips" name="trips" required>
                    <option value="">Select a location and date first</option>
                </select>
            </div>
            <div class="form-group">
                <label for="persons">Number of Persons:</label>
                <input type="number" class="form-control" id="persons" name="persons" min="1" max="3" required>
            </div>
            <button type="submit" class="btn btn-primary">Book Ticket</button>
        </form>
    </div>
    <?php require_once('../app/components/footer.php');?>
    <script>
        $(document).ready(function() {
            $('#ticket-form').submit(function(event) {
                event.preventDefault(); // Prevent default form submission

                var formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    type: 'POST',
                    url: 'fetchAvailableTrips', // URL of the PHP script for fetching trips
                    data: formData,
                    success: function(response) {
                        $('#trips').html(response); // Update the trips select dropdown with the response
                    }
                });
            });

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
                    $('#trips').html('<option value="">Select a location and date first</option>'); // Reset the trips select dropdown
                }
            });
        });
    </script>
    </body>
</html>
