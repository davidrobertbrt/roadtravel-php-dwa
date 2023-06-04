<html>
    <head>
        <?php require_once('../app/components/header.php');?>
    </head>
    <body>
        <?php require_once('../app/components/navbar.php');?>
        <div class = "container mt-4">
        <div class="container">
            <h2>Adaugă un nou autobuz</h2>
                    <form method="POST" action="create">
                        <div class="form-group">
                            <label for="nrSeats">Număr locuri:</label>
                            <input type="text" class="form-control" id="nrSeats" placeholder="Enter number of seats" name="nrSeats">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                <h2>Table</h2>
                <table class="table">
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>Locuri</th>
                        <th>Acțiuni</th>
                        </tr>
                    </thead>
                    <?php
                        if(!empty($data['buses'])) {
                    ?>
                    <tbody>
                        <?php
                            foreach ($data['buses'] as $bus) {
                            $id = $bus->getId();
                            $nrSeats = $bus->getNrSeats();
                        ?>
                        <tr>
                            <td><?php echo $id; ?></td>
                            <td><?php echo $nrSeats; ?></td>
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
