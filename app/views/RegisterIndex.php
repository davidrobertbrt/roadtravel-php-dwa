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
                        <label for="firstName">Numele de familie</label>
                        <input type="text" class="form-control" id="firstName" placeholder="Nume">
                        </div>
                        <div class="form-group col-md-6">
                        <label for="lastName">Prenume</label>
                        <input type="text" class="form-control" id="lastName" placeholder="Prenume">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Adresa de e-mail</label>
                        <input type="email" class="form-control" id="email" placeholder="E-mail">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                        <label for="password">Parola</label>
                        <input type="password" class="form-control" id="password" placeholder="Parola">
                        </div>
                        <div class="form-group col-md-6">
                        <label for="confirmPassword">Confirmă parola</label>
                        <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm Password">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                        <label for="dateOfBirth">Data nașterii</label>
                        <input type="date" class="form-control" id="dateOfBirth">
                        </div>
                        <div class="form-group col-md-6">
                        <label for="phoneNumber">Număr de telefon</label>
                        <input type="tel" class="form-control" id="phoneNumber" placeholder="Număr de telefon">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address">Adresă</label>
                        <textarea class="form-control" id="address" rows="3"></textarea>
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