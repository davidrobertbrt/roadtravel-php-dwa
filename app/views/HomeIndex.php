<html>
<head>
    <?php require_once('../app/components/header.php'); ?>
</head>
<body>
    <?php require_once('../app/components/navbar.php'); ?>
    <div class="container mt-4">
        <div class="container">
            <h3>Statistics of the website</h3>
            <p>If no data present, please hit the refresh button.</p>
            <?php if(isset($data['stats'])){?>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th>Statistic</th>
                            <th>Value</th>
                        </tr>
                        <tr>
                            <td>Total Hits</td>
                            <td><?php echo $data['stats']['totalHits'];?></td>
                        </tr>
                        <tr>
                            <td>Unique Visitors</td>
                            <td><?php echo $data['stats']['uniqueVisitors'];?></td>
                        </tr>
                        <tr>
                            <td>Mean Hits per Visitor</td>
                            <td><?php echo $data['stats']['meanHitsPerVisitor'];?></td>
                        </tr>
                        <tr>
                            <td>Maximum Hits per Day</td>
                            <td><?php echo $data['stats']['maxHitsPerDay'];?></td>
                        </tr>
                        <tr>
                            <td>Average Hits per Day</td>
                            <td><?php echo $data['stats']['averageHitsPerDay'];?></td>
                        </tr>
                        <tr>
                            <td>Standard Deviation of Hits per Day</td>
                            <td><?php echo $data['stats']['stdDevHitsPerDay'];?></td>
                        </tr>
                    </table>
                <?php }?>
                    <form method = "GET" action = "generate">
                        <button type="submit" class="btn btn-primary">Refresh Statistics</button>
                    </form>
                    <form method = "GET" action = "print">
                        <button type="submit" class="btn btn-primary">Print Statistics</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="container">
            <form id="weatherForm" class="mt-4" method = "POST" action = "fetchWeather">
                <div class="form-group">
                    <label for="cityInput">City:</label>
                    <input type="text" class="form-control" id="cityInput" placeholder="Enter city name" required name="city">
                </div>
                <div class="form-group">
                    <p>Data is fetched using OpenMeteo API</p>
                </div>
                <button type="submit" class="btn btn-primary">Get Weather</button>
            </form>
            <?php if (isset($data['weather'])) { ?>
                <div id="weatherResult" class="mt-4">
                    <h3>Weather Information</h3>
                    <table class="table">
                        <tbody>
                            <?php foreach ($data['weather'] as $entry) { ?>
                                <tr>
                                    <th>Date:</th>
                                    <td id="date"><?php echo $entry['date']->format('Y-m-d') ?></td>
                                </tr>
                                <tr>
                                    <th>Temperature:</th>
                                    <td id="temperature"><?php echo $entry['temperature'] ?></td>
                                </tr>
                                <tr>
                                    <th>Weather Code:</th>
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
