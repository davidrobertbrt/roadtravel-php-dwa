<html>
<head>
    <?php require_once('../app/components/header.php'); ?>
</head>
<body>
    <?php require_once('../app/components/navbar.php'); ?>
    <div class="container mt-4">
        <div class="container">
            <h3>Statistica site-ului</h3>
            <p>Dacă datele nu sunt prezente, apasă refresh</p>
            <?php if(isset($data['stats'])){?>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th>Statistică</th>
                            <th>Valoare</th>
                        </tr>
                        <tr>
                        <td>Total de Accesări</td>
                        <td><?php echo $data['stats']['totalHits'];?></td>
                        </tr>
                        <tr>
                            <td>Vizitatori Unici</td>
                            <td><?php echo $data['stats']['uniqueVisitors'];?></td>
                        </tr>
                        <tr>
                            <td>Media Accesărilor pe Vizitator</td>
                            <td><?php echo $data['stats']['meanHitsPerVisitor'];?></td>
                        </tr>
                        <tr>
                            <td>Maximul de Accesări pe Zi</td>
                            <td><?php echo $data['stats']['maxHitsPerDay'];?></td>
                        </tr>
                        <tr>
                            <td>Media Accesărilor pe Zi</td>
                            <td><?php echo $data['stats']['averageHitsPerDay'];?></td>
                        </tr>
                        <tr>
                            <td>Deviația Standard a Accesărilor pe Zi</td>
                            <td><?php echo $data['stats']['stdDevHitsPerDay'];?></td>
                        </tr>
                    </table>
                <?php }?>
                    <form method = "GET" action = "<?php echo $urlPath . 'home/generate'?>">
                        <button type="submit" class="btn btn-primary">Refresh</button>
                    </form>
                    <form method = "GET" action = "<?php echo $urlPath . 'home/print'?>">
                        <button type="submit" class="btn btn-primary">Print</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="container">
            <form id="weatherForm" class="mt-4" method = "POST" action = "<?php echo $urlPath . 'home/fetchWeather'?>">
                <div class="form-group">
                    <label for="cityInput">Oraș:</label>
                    <input type="text" class="form-control" id="cityInput" placeholder="Introdu orașul" required name="city">
                </div>
                <div class="form-group">
                    <p>Datele sunt preluate după OpenMeteo folosind API-ul lor</p>
                </div>
                <button type="submit" class="btn btn-primary">Preia vremea</button>
            </form>
            <?php if (isset($data['weather'])) { ?>
                <div id="weatherResult" class="mt-4">
                    <h3>Informații vreme</h3>
                    <table class="table">
                        <tbody>
                            <?php foreach ($data['weather'] as $entry) { ?>
                                <tr>
                                    <th>Dată:</th>
                                    <td id="date"><?php echo $entry['date']->format('Y-m-d') ?></td>
                                </tr>
                                <tr>
                                    <th>Temperatură:</th>
                                    <td id="temperature"><?php echo $entry['temperature'] ?></td>
                                </tr>
                                <tr>
                                    <th>Cod de vreme:</th>
                                    <td id="weatherCode"><?php echo $entry['code']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php require_once('../app/components/footer.php'); ?>
</body>
</html>
