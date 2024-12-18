<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>PHP Weather Forecaster</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="report-container">
            <?php
            function getForecast($city) {
                $country = "US";
                $city = preg_replace('/\s+/', '', $city);
                $url = "http://api.openweathermap.org/data/2.5/forecast/daily?q=" . $city . "," . $country . "&units=metric&cnt=1&lang=en&appid=c0c4a4b4047b97ebc5948ac9c48c0559";
                $json = file_get_contents($url);
                $data = json_decode($json, true);
                $weather = array("description" => $data['list'][0]['weather'][0]['description'], "icon" => $data['list'][0]['weather'][0]['icon'],
                    "maxtemp" => $data['list'][0]['temp']['max'], "mintemp" => $data['list'][0]['temp']['min'],
                    "humidity" => $data['list'][0]['humidity'], "speed" => $data['list'][0]['speed']);
                return $weather;
            }
            
            function convertCelsiusToFahrenheit($celsius) {
                return ($celsius * 9/5) + 32;
            }
           
            if (isset($_POST['city'])) {
                $city = $_POST['city'];
                $forecast = getForecast($city);
                
                if ($forecast) {
                    $currentTime = time();                    
                    echo '
                    <h1 class="lato-bold">' . $city . ' Weather Forecast</h2>
                    <div class="time roboto-regular">
                        <div>' . date("l g:i a", $currentTime) . '</div>
                        <div>' . date("jS F, Y", $currentTime) . '</div>
                        <div>' . ucwords($forecast['description']) . '</div>
                    </div>
                    <div class="weather-forecast roboto-regular">
                        <img src="https://openweathermap.org/img/w/' . $forecast['icon'] . '.png" class="weather-icon" />
                    High ' . convertCelsiusToFahrenheit($forecast['maxtemp']) . '°F <span class="min-temperature"> 
                    Low ' . convertCelsiusToFahrenheit($forecast['mintemp']) . '°F</span>
                </div>
                <div class="time roboto-regular">
                    <div>Humidity: ' . $forecast['humidity'] . ' %</div>
                    <div>Wind: ' . $forecast['speed'] . ' km/h</div>
                </div>';
                }
            } else {
                echo '
                    <h1 class="lato-bold">Weather Forecaster</h1>
                    <form class="roboto-regular" method="POST" action="index.php">
                        <input type="text" placeholder="City" name="city"/><br/><br/>
                        <input type="submit" name="forecast" value="Get Forecast"/>
                    </form>
                ';
            }
            ?>
        </div>
    </body>
</html>
