<?

if (!isset($_SESSION['support']['pswdReset'])) {
	echo "<h1>Why have you done that then?</h1><h3>To reset your password please use the link sent to your email address.</h3>";
	die();
}

?>

<div class="overBoxTitle">
	<h1>Please set your new password</h1>
	<h3>Remember: passwords must be more than 8 characters and include a mixture of cases and numbers</h3>
</div>

<div id="accInfo" class="overBoxPage">

	<form id="updatePswd" action="" class="basicForm">
		
		<fieldset>

			<div class="formRow">
				<label for="newPassword">your new password:</label>
				<input class="inputField" type="password" name="newPassword">
			</div>

			<div class="formRow">
				<label for="newPasswordConfirm">confirm your new password:</label>
				<input class="inputField" type="password" name="newPasswordConfirm">
			</div>

			<button name="submit" type="submit">change password</button>

		</fieldset>
		
	</form>


</div>