<html>
    <head>
        <?php require_once('../app/components/header.php');?>
    </head>
    <body>
        <?php require_once('../app/components/navbar.php');?>
        <div class = "container mt-4">
        <?php
            $crBus = $data['crBus'];
            if(!empty($crBus)) {
        ?>
                <form method="POST" action="process">
                        <?php
                            $id = $crBus->getId();
                            $nrSeats = $crBus->getNrSeats();
                        ?>
                        <div class="form-group">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <label for="nrSeats">Număr locuri:</label>
                            <input type="text" class="form-control" id="nrSeats" placeholder="Introdu nr locuri" name="nrSeats" value="<?php echo $nrSeats; ?>">
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
