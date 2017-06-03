<?

$usrId = $_SESSION['customs']['userId'];
$db = new Database();
$db->select("id, username, dateCreated, firstName, lastName, dob, email", "users", "", "id=".$usrId, "");
$db->select("id, name, abbr, baseNo", "units", "", "id!=0", "");
$db->flatSelect("unitId", "favUnits", "userId=".$usrId, "");

function apostropheCheck($nameIn){

	$owner = (substr($nameIn, -1) != 's' ?  $nameIn.'\'s' : $nameIn.'\'');
	return $owner;

}

?>

<div class="overBoxTitle">
	<h1><i class="fa fa-user"></i> <?=apostropheCheck($db->users[$usrId]->username)?> Account Settings</h1>
</div>

<div id="accInfo" class="overBoxPage">









	<form id="updateUsrInfo" action="" class="basicForm">
		
		<fieldset>
			
			<legend>1. Your info</legend>

			<div class="formRow">
				<label for="email">username:</label>
				<input class="inputField" type="text" name="username" value="<?=$db->users[$usrId]->username?>">
			</div>

			<div class="formRow">
				<label for="email">email:</label>
				<input class="inputField" type="email" name="email" value="<?=$db->users[$usrId]->email?>">
			</div>


			<input type=hidden name=id value=<?=$usrId?> />
			<button name="submit" type="submit">update</button>

		</fieldset>
		
	</form>





	<form id="updatePswd" action="" class="basicForm">
		
		<fieldset>
			
			<legend>2. Change Your Password</legend>

			<div class="formRow">
				<label for="password">your current password</label>
				<input class="inputField" type="password" name="password" value="">
			</div>

			<div class="formRow">
				<label for="newPassword">your new password:</label>
				<input class="inputField" type="password" name="newPassword" value="">
			</div>

			<div class="formRow">
				<label for="newPasswordConfirm">confirm your new password:</label>
				<input class="inputField" type="password" name="newPasswordConfirm" value="">
			</div>

			<button name="submit" type="submit">change password</button>

		</fieldset>
		
	</form>

	<form id="updateFavUnits" action="" class="basicForm">
		<fieldset>

			<legend>3. Your favorite units</legend>
		
			<? 
			foreach($db->units as $key => $value){

				$unitSel = '';
				$checkedClass = '';
				if (in_array($key, $db->favUnits)) {
					$unitSel = 'checked="checked"';
					$checkedClass = 'selectedBox';
				}

			?>
			<div class="formRow <?=$checkedClass?>">
				<label><input class name="<?=$key?>" type="checkbox" value="<?=$key?>" <?=$unitSel?>/><?=$value->name?></label>
			</div>
			<?
			} ?>
			<button name="submit" type="submit">save units</button>

		</fieldset>
		
	</form>

</div>