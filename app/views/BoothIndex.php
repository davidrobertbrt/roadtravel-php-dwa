<html>
    <head>
        <?php require_once('../app/components/header.php');?>
    </head>
    <body>
        <?php require_once('../app/components/navbar.php');?>
        <div class = "container mt-4">
           <h2>Bilete</h2>
            <?php
                if(empty($data['results']))
                {
                    echo '<p>No bookings are made!</p>';
                }
                else{
            ?>

           <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Plecare</th>
                        <th>Sosire</th>
                        <th>Dată plecare</th>
                        <th>Dată sosire</th>
                        <th>Număr persoane</th>
                        <th>Acțiuni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['results'] as $result){?>
                    <tr>
                        <td><?php echo $result['id'];?></td>
                        <td><?php echo $result['email'];?></td>
                        <td><?php echo $result['departureCity'];?></td>
                        <td><?php echo $result['arrivalCity'];?></td>
                        <td><?php echo $result['departureDate'];?></td>
                        <td><?php echo $result['arrivalDate'];?></td>
                        <td><?php echo $result['noPersons'];?></td>
                        <td>
                            <form action="delete" method="POST" class="inline-form">
                                    <input type="hidden" name="id" value="<?php echo $result['id'];?>">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
           </table>
           <?php
                }
           ?>
        </div>
        <?php require_once('../app/components/footer.php');?>
    </body>
</html>