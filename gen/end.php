</main>

<? include_once(__DIR__."/footer.php");
?>

<div id="notices"></div>

<div id="disclaimerBox">
	<div id="contents">
		<div class="overBoxTitle">
			<img alt="my Cocktailor" src="<?=$setup->getSiteRoot();?>img/myCocktailorLogo.svg" onerror="this.onerror=null; this.src='<?=$setup->getSiteRoot();?>img/myCocktailorLogo.png'">
		</div>
		<h1><?=Setup::siteName?></h1>
		<div class="overBoxPage">
			<p>To use this site, you must be <strong>above</strong> the legal drinking age in your country of residence.</p>
			<p>You also agree to the sites <a href="./support/terms">terms</a> and use of <a href="./support/cookies">cookies</a></p>
			<button id="disclaimerBtn" type="button">enter</button>
			<p>Please drink cocktails responsibly.</p>
			<p>Be <a href="https://www.drinkaware.co.uk/" target="_blank">drinkaware</a></p>
		</div>
	</div>
</div>
<div id="overBox"><div id="contents"></div><a class="closeBtn"><i id="closeX" class="fa fa-times"></i></a></div>

<?=$deployJs?>

<script>
<?

	////RUN ANY BUILD NOTICES////
	$noticeQueue = $_SESSION['noticeQueue'];
	foreach ($noticeQueue as $key => $value) {
		echo 'displayNotice("', $noticeQueue[$key][0], '", ', $noticeQueue[$key][1], ', "', $noticeQueue[$key][2], '", "', $noticeQueue[$key][3], '");';
	}
	$_SESSION['noticeQueue'] = array();

	////CHECK & RUN GET REQUESTS////
	if (isset($_GET['data'])) {

		$requestArr = explode("/", $_GET['data']);
		$requestType = $requestArr[0];
		$requestTask = $requestArr[1];
		if (isset($requestArr[2])) {
			$requestTask = $requestArr[1].'/'.$requestArr[2];
		}


		if ($requestType == 'recipes') {
			echo 'overBoxCall("', $requestTask, '", null, "recipe");';
		}else if ($requestType == 'support'){
			echo 'overBoxCall("', $requestTask, '", null, "support");';
		}

	}

?>
</script>

<?
// Networked Livereload.
// if(isset($_SERVER['SERVER_NAME']) &&  $_SERVER['SERVER_NAME'] == "192.168.220.150"){

// 	echo '<script src="http://192.168.220.150:35729/livereload.js?snipver=1"></script>';
// }elseif (isset($_SERVER['SERVER_NAME']) &&  $_SERVER['SERVER_NAME'] == "10.0.1.150"){

// 	echo '<script src="http://10.0.1.150:35729/livereload.js?snipver=1"></script>';
// }


?>

</body>
</html>