<html>
    <head>
        <?php require_once('../app/components/header.php');?>
    </head>
    <body>
    <div class="container">
            <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                <div class="card-header">
                    Resetează parola
                </div>
                <div class="card-body">
                    <form method = "POST" action = "send">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Introdu email">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Resetare</button>
                    </form>
                </div>
                </div>
            </div>
            </div>
        </div>
    </body>
</html>