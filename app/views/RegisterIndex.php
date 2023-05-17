<html>
    <head>
        <?php require_once('../app/components/header.php');?>
        <title>Register - RoadTravel</title>
    </head>
    <body>
            <div class="container">
            <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                <div class="card-header">
                    Înregistrare
                </div>
                <div class="card-body">
                    <form method = "POST" action = "./../register/process">
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
                    <div class="form-group">
                        <label for="email">Adresa de e-mail</label>
                        <input type="email" class="form-control" id="email" placeholder="E-mail" name="emailAddress">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                        <label for="password">Parola</label>
                        <input type="password" class="form-control" id="password" placeholder="Parola" name="password">
                        </div>
                        <div class="form-group col-md-6">
                        <label for="confirmPassword">Confirmă parola</label>
                        <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm Password" name="confirmPassword">
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
                    <button type="submit" class="btn btn-primary btn-block">Înregistrează-te</button>
                    </form>
                </div>
                </div>
            </div>
            </div>
        </div>
    </body>
</html>