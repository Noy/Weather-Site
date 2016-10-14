<?php
	
	// Setting the weather variable empty first, as for the error and city ones.
	$weather = "";
	$error = "";
	$city = "";

	// First we check if somebody has actually entered anything in the input, otherwise we get a notice.
	if (array_key_exists('city', $_GET)) {
		// Get the value they entered and replace any spaces they put.
		$city = str_replace(' ', '', $_GET['city']);
		// Getting the headers of the website we're suing, plus our 'city' variable
		$file_headers = @get_headers("http://www.weather-forecast.com/locations/".$city."/forecasts/latest");

		// Checking if anything even appears on the page we are about to load
		if ($file_headers[0] == 'HTTP/1.1 404 Not Found') {
			// If not, show an error message.
			$error = "That city could not be found.";
		} else {
			// This will be our API, just using a simple weather forecast website.
			$forecast = file_get_contents("http://www.weather-forecast.com/locations/".$city."/forecasts/latest");
			// We find all the content above the first string we passed in the 'explode' function.
			$aboveTarget = explode('3 Day Weather Forecast Summary:</b><span class="read-more-small"><span class="read-more-content"> <span class="phrase">', $forecast);
			// Checking if that target is more than 1
			if (sizeof($aboveTarget) > 1) {
				// If so, check for the content underneith.
				$underTarget = explode('</span></span></span>', $aboveTarget[1]);
				// Checking if this is more than 1
				if (sizeof($underTarget) > 1) {
					// Finally set the weather (target) to just that string of text we first set
					$weather = $underTarget[0];
				// If not, print the same error message	
				} else {
					$error = "That city could not be found.";
				}
			} else {
				$error = "That city could not be found.";
			}
		}
	} else {
		// This sets our value to empty, otherwise it would just by default be a PHP notice the first time they load the page.
		$_GET['city'] = "";
	}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css" integrity="2hfp1SzUoho7/TsGGGDaFdsuuDL0LX2hnUp6VkX3CUQ2K4K+xjboZdsXyp4oUHZj" crossorigin="anonymous">
    <title>What's the Weather?</title>
    <style type="text/css">
    	html {
    		background: url("background.jpg") no-repeat center center fixed;
    		-webkit-background-size: cover;
    		-moz-background-size: cover;
    		-o-background-size: cover;
    		background-size: cover;
    	}
    	body {
    		background: none;
    	}
    	.container {
    		text-align: center;
    		margin-top: 130px;
    		width: 500px;
    	}
    	h1 {
    		font-weight: bold;
    		color: #242424;
    	}
    	input {
    		margin: 20px 0px;
    	}

    	#weather {
    		margin-top: 15px;
    	}
    </style>
  </head>
  <body>
  	<div class="container">
  		<h1>What's The Weather?</h1>
  		<form>
  			<div class="form-group">
    			<label for="city">Enter the name of a city.</label>
    			<input type="text" class="form-control" name="city" id="city" value="<?php echo $_GET['city']; ?>" placeholder="E.g. Los Angeles, Paris">
  			</div>
  			<button type="submit" class="btn btn-primary">Submit</button>
		</form>
		<div id="weather">
							<?php 
								if ($weather) echo '<div class="alert alert-success" role="alert">'.$weather.'</div>';
								if ($error) echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
							?>
		</div>
  	</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js" integrity="sha384-THPy051/pYDQGanwU6poAc/hOdQxjnOEXzbT+OuUAFqNqFjL+4IGLBgCJC3ZOShY" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.2.0/js/tether.min.js" integrity="sha384-Plbmg8JY28KFelvJVai01l8WyZzrYWG825m+cZ0eDDS1f7d/js6ikvy1+X+guPIB" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/js/bootstrap.min.js" integrity="VjEeINv9OSwtWFLAtmc4JCtEJXXBub00gtSnszmspDLCtC0I4z4nqz7rEFbIZLLU" crossorigin="anonymous"></script>
  </body>
</html>