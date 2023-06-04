<html>
    <head>
        <?php require_once('../app/components/header.php');?>
    </head>
    <body>
        <?php require_once('../app/components/navbar.php');?>
        <div class = "container mt-4">
        <div class="container">
            <h2>Realizează un nou cupon</h2>
                    <form method="POST" action="create">
                        <div class="form-group">
                            <label for="discountFactor">Factor reducere</label>
                            <input type="range" class="form-range" id="discountFactor" placeholder="Introdu factorul" name="discountFactor" min="0" max = "1" step="0.1">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                <h2>Table</h2>
                <table class="table">
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>Factor reducere</th>
                        <th>Folosit</th>
                        <th>Acțiuni</th>
                        </tr>
                    </thead>
                    <?php
                        if(!empty($data['discountRepo'])) {
                    ?>
                    <tbody>
                        <?php
                            foreach ($data['discountRepo'] as $discount) {
                                $id = $discount->getId();
                                $factor = $discount->getFactor();
                                $used = $discount->getUsed();
                        ?>
                        <tr>
                            <td><?php echo $id; ?></td>
                            <td><?php echo $factor; ?></td>
                            <td><?php echo $used; ?></td>
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
