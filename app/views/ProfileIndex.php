<html>
    <head>
        <?php require_once('../app/components/header.php');?>
    </head>
    <body>
        <?php require_once('../app/components/navbar.php');?>
        <div class = "container mt-4">
            <div class="container">
                <h2>Editează profilul</h2>
                <div class = "card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                            <th>Nume</th>
                            <th>Prenume</th>
                            <th>Email</th>
                            <th>Numar de telefon</th>
                            <th>Adresa</th>
                            <th>Data nasterii</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $data['crUser']->getFirstName();?></td>
                                <td><?php echo $data['crUser']->getLastName();?></td>
                                <td><?php echo $data['crUser']->getEmailAddress();?></td>
                                <td><?php echo $data['crUser']->getPhoneNumber();?></td>
                                <td><?php echo $data['crUser']->getAddress();?></td>
                                <td><?php echo $data['crUser']->getDateOfBirth();?></td>
                            </tr>
                        </tbody>
                        </table>
                </div>


                <div class="card-body">
                    <form method = "POST" action = "submit">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                            <label for="firstName">Prenume</label>
                            <input type="text" class="form-control" id="firstName" placeholder="Prenume" name="firstName">
                            </div>
                            <div class="form-group col-md-6">
                            <label for="lastName">Nume de fam.</label>
                            <input type="text" class="form-control" id="lastName" placeholder="Nume" name="lastName">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                            <label for="dateOfBirth">Data nașterii</label>
                            <input type="date" class="form-control" id="dateOfBirth" name="dateOfBirth">
                            </div>
                            <div class="form-group col-md-6">
                            <label for="phoneNumber">Număr de telefon</label>
                            <input type="tel" class="form-control" id="phoneNumber" placeholder="Număr de telefon" name="phoneNumber">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address">Adresă</label>
                            <textarea class="form-control" id="address" rows="3" name="address"></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                            <label for="password">Confirmă parola</label>
                            <input type="password" class="form-control" id="password" placeholder="Parola" name="password">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </form>
                    </div>
            </div>
        </div>
        <?php require_once('../app/components/footer.php');?>
    </body>
</html>