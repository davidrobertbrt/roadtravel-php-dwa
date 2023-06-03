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
                    Introdu parola noua.
                </div>
                <div class="card-body">
                    <?php 
                     $urlPath = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';
                    ?>
                    <form method = "POST" action = "<?php echo $urlPath . 'reset/process/'?>">
                    <div class="form-group">
                        <label for="password">Parola</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Introdu parola">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="confirmPassword">Confirmă parola</label>
                        <input type="password" class="form-control" id="confirmPassword" placeholder="Confirmă parola" name="confirmPassword">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Trimite</button>
                    </form>
                </div>
                </div>
            </div>
            </div>
        </div>
    </body>
</html>