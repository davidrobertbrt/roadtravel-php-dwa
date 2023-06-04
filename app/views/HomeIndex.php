<html>
<head>
    <?php require_once('../app/components/header.php'); ?>
</head>
<body>
    <?php require_once('../app/components/navbar.php'); ?>
    <div class="container mt-4">
        <div class="container">
            <h3>Statistics of the website</h3>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th>Statistic</th>
                            <th>Value</th>
                        </tr>
                        <tr>
                            <td>Total Hits</td>
                            <td>111</td>
                        </tr>
                        <tr>
                            <td>Unique Visitors</td>
                            <td>111</td>
                        </tr>
                        <tr>
                            <td>Mean Hits per Visitor</td>
                            <td>111</td>
                        </tr>
                        <tr>
                            <td>Maximum Hits per Day</td>
                            <td>111</td>
                        </tr>
                        <tr>
                            <td>Average Hits per Day</td>
                            <td>111</td>
                        </tr>
                        <tr>
                            <td>Standard Deviation of Hits per Day</td>
                            <td>111</td>
                        </tr>
                    </table>
                    <button class="btn btn-primary" onclick="window.location.reload();">Refresh Statistics</button>
                    <button class="btn btn-primary" onclick="window.print();">Print Statistics</button>
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
