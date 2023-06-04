<html>
    <head>
        <?php require_once('../app/components/header.php');?>
    </head>
    <body>
        <?php require_once('../app/components/navbar.php');?>
        <div class = "container mt-4">
            <h2>Utilizatori</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nume</th>
                        <th>Email</th>
                        <th>Adresă</th>
                        <th>Număr telefon</th>
                        <th>Data nașterii</th>
                        <th>Rol</th>
                        <th>Acțiune</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($data['results'] as $result){
                    ?>
                    <tr>
                        <td><?php echo $result['id'];?></td>
                        <td><?php echo $result['name'];?></td>
                        <td><?php echo $result['email'];?></td>
                        <td><?php echo $result['address'];?></td>
                        <td><?php echo $result['phoneNumber'];?></td>
                        <td><?php echo $result['dateOfBirth'];?></td>
                        <td><?php echo $result['role'];?></td>
                        <td>
                            <?php if($result['role'] === 'default'){?>
                            <form action="promote" method="POST" class="inline-form">
                                <input type="hidden" name="id" value="<?php echo $result['id'];?>">
                                <!-- Include other fields for editing -->
                                <button type="submit" class="btn btn-primary">Promovează</button>
                            </form>
                            <?php }?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php require_once('../app/components/footer.php');?>
    </body>
</html>