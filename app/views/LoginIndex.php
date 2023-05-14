<html>
    <head>
        <?php require_once('../app/components/header.php');?>
        <title>Login - RoadTravel</title>
    </head>
    <body>
            <div class="container">
            <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                <div class="card-header">
                    Conectare
                </div>
                <div class="card-body">
                    <form method = "POST" action = "./../login/process">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Introdu email">
                    </div>
                    <div class="form-group">
                        <label for="password">Parola</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Introdu parola">
                    </div>
                    <div class = "form-group">
                        <a href = "./../login/reset">Ai uitat parola?</a>
                    </div>
                    <div class = "form-group">
                        <a href = "./../register/index">Înregistrează-te aici</a>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </form>
                </div>
                </div>
            </div>
            </div>
        </div>
    </body>
</html>