<?

$usrId = $_SESSION['customs']['userId'];
$usrName = $_SESSION['customs']['username'];

$thisRecipe = new stdClass();

$db = new Database();
$db->flatSelect("unitId", "favUnits", "userId=".$usrId, "Selects");

if(isset($dataInArr['id'])){
	
	$db->select("id, creatorId, name, year, bgColour, author, method", "cocktails", "", "id=".$dataInArr['id'], "");
	$thisRecipe = $db->cocktails[$dataInArr['id']];
	$db->select("id, name, quantity, unitId", "ingredients", "", "cocktailId=".$dataInArr['id'], "");
	$db->flatSelect("unitId", "ingredients", "cocktailId=".$dataInArr['id'], "Selects");
	$unitSelects = array_merge($db->favUnitsSelects,$db->ingredientsSelects, array(0));
}else{
	$unitSelects = array_merge($db->favUnitsSelects, array(0));;
}

$db->select("id, abbr", "units", "", "id in (".implode(",", $unitSelects).")", "");


//$unitArr = $arrayName = array(0 => 'unit', 1 => 'ml', 2 => 'cl', 3 => 'fl oz');


function getData($prop){
	global $thisRecipe;
	if (isset($thisRecipe->$prop)) {
		$propVal = $thisRecipe->$prop;
	}else{
		$propVal = '';
	}
	return $propVal;
}


?>

<form id="addNewRecipe" action="" class="basicForm">

<?
if(isset($dataInArr['id'])){
	echo '<input type="hidden" name="id" value="'.$dataInArr['id'].'">';
}
?>

<input type="hidden" name="creatorId" value="<?=$usrId?>">

	<div id="newCocktailName" class="overBoxTitle">
		<textarea name="name" placeholder="Your New Cocktail..."><?=getData('name')?></textarea>
	</div>

	<div id="ings" class="overBoxPage">
		<ul id="ingList">
			<li class="ingTemplate">
				<div class="ingName">
					<input type="text" name="ing-name" placeholder="name" />			
				</div>
				<div class="ingUnit">
					<select name="ing-unitId" class="unitSelect">
						<option value="0">unit</option>
					<? 
					foreach ($db->units as $unitkey => $unitValues) { 
						$selected = (isset($dataInArr['id']) && $value->unitId == $unitkey ?'selected="selected"':'');
					?>
						<option value="<?=$unitkey?>" <?=$selected?>><?=$unitValues->abbr?></option>
					<? } ?>
					</select>
				</div>
				<div class="ingQty">
				
					<input type="number" name="ing-quantity" placeholder="qty" />
				</div>
			</li>

			<?
			if (isset($db->ingredients)) {
				foreach ($db->ingredients as $key => $value) {
				?>
				<li class="ingRow">
					<div class="ingName">
						<input type="text" name="ing-name" placeholder="name" value="<?=$value->name?>"/>			
					</div>
					<div class="ingUnit">
						<select name="ing-unitId" class="unitSelect">
						<? 
						foreach ($db->units as $unitkey => $unitValues) { 
							$selected = (isset($dataInArr['id']) && $value->unitId == $unitkey ?'selected="selected"':'');
						?>
							<option value="<?=$unitkey?>" <?=$selected?>><?=$unitValues->abbr?></option>
						<? } ?>
						</select>
					</div>
					<div class="ingQty">
						<input type="number" name="ing-quantity" placeholder="qty" value="<?=$value->quantity?>"/>
					</div>
				</li>	
				<?
				}
			}
			?>

		</ul>

		<button id="delIng"><i class="fa fa-minus-circle"></i><span> Ingredient</span></button>
		<button id="addIng"><i class="fa fa-plus-circle"></i><span> Ingredient</span></button>

		<?
		if (isset($db->ingredients)){
			echo "<script>$('#delIng').css({display: 'block'}); $('#addIng').css({width: '48%'});</script>";
		}
		?>

	</div>

	<div id="method">
		<textarea name="method" placeholder="Method..."><?=getData('method')?></textarea>
	</div>

	<div id="author">
		<input type="text" name="author" placeholder="author" value="<?=isset($thisRecipe->author)? $thisRecipe->author : $usrName?>">, 
		<input type="text" name="year" placeholder="year" value="<?=isset($thisRecipe->year)? $thisRecipe->year : date('Y')?>">
	</div>

	<div id="colorPicker">

		<?
			$colorArr = array("silver", "slate", "ruby", "emerald", "sapphire", "yellow", "amber", "bronze");

			$i=1;
			foreach ($colorArr as $value) {
				if (getData('bgColour')!='' && $thisRecipe->bgColour == $value) {
					$checked = 'checked="checked"';
					echo '<script>$("#overBox").removeClass().addClass("BG-'.$thisRecipe->bgColour.'");</script>';

				}else{
					$checked ='';
				}

				
				echo '<input '.$checked.' type="radio" name="bgColour" value="'.$value.'" id="r'.$i.'"/><label class="colorPickBox BG-'.$value.'" for="r'.$i.'"></label>';
				++$i;
			}
		?>

	</div>

	<button name="submit" type="submit"><i class="fa fa-sign-in fa-rotate-90"></i><span> Save</span></button>

</form>