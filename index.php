<?php /* index.php ( ShortURL implementation ) */

include('config/conf.php'); // <- specific settings
include('classes/ShortUrl.php'); // <- ShortUrl class file

$ShortUrl = new ShortUrl();
$msg = '';

// if the form has been submitted
if ( isset($_POST['longurl']) )
{
	// escape bad characters from the user's url
	$longurl = trim(mysql_escape_string($_POST['longurl']));

	// set the protocol to not ok by default
	$protocol_ok = false;
	
	// if there's a list of allowed protocols, 
	// check to make sure that the user's url uses one of them
	if ( count($allowed_protocols) )
	{
		foreach ( $allowed_protocols as $ap )
		{
			if ( strtolower(substr($longurl, 0, strlen($ap))) == strtolower($ap) )
			{
				$protocol_ok = true;
				break;
			}
		}
	}
	else // if there's no protocol list, screw all that
	{
		$protocol_ok = true;
	}
		
	// add the url to the database
	if ( $protocol_ok && $ShortUrl->add_url($longurl) )
	{
		$url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'?id='.$ShortUrl->get_id($longurl);
		$msg = '<p class="success">URL is: <a href="'.$url.'">'.$url.'</a></p>';
	}
	elseif ( !$protocol_ok )
	{
		$msg = '<p class="error">Invalid protocol!</p>';
	}
	else
	{
		$msg = '<p class="error">Creation of your Short URL failed for some reason.</p>';
	}
}
else // if the form hasn't been submitted, look for an id to redirect to
{
	if ( isSet($_GET['id']) ) // check GET first
	{
		$id = mysql_escape_string($_GET['id']);
	}
	else // otherwise, just make it empty
	{
		$id = '';
	}
	
	// if the id isn't empty and it's not this file, redirect to it's url
	if ( $id != '' && $id != basename($_SERVER['PHP_SELF']) )
	{
		$location = $ShortUrl->get_url($id);
		
		if ( $location != -1 )
		{
			header('Location: '.$location);
		}
		else
		{
			$msg = '<p class="error">Sorry, but that lil&#180; URL isn\'t in our database.</p>';
		}
	}
}

// print the form

?>
<!DOCTYPE html>

<html>

	<head>
		<title>URL Shortner</title>
		<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />

	</head>
	
	<body>
		
		<h1>URL Shortner</h1>
		<br />
		<?php echo $msg; ?>
		<br />
		<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
		
			<fieldset>
				<label for="longurl">Enter a long URL:</label>
				<input type="text" name="longurl" id="longurl" />
				<input type="submit" name="submit" id="submit" value="Short it!" />
			</fieldset>
		
		</form>		
	
	</body>

</html>