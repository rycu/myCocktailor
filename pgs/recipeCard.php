<?

	$usrId = isset($_SESSION['customs']['userId'])? $_SESSION['customs']['userId'] : '';

	$unitArr = array(1 => 'ml', 2 => 'cl', 3 => 'fl oz');

	$cocktailId = urldecode($recipeSlug);

	$db = new Database();

	//$db->exists('users', "id='1'");

	$db->select("id, creatorId, name, year, bgColour, author, method", "cocktails", "", "id='$cocktailId'", "");
	$db->select("id, name, quantity, unitId", "ingredients", "", "cocktailId=".end($db->cocktails)->id, "");

	$db->flatSelect("unitId", "favUnits", "userId=".$usrId, "Selects");
	$db->flatSelect("unitId", "ingredients", "cocktailId=".end($db->cocktails)->id, "Selects");


	$db->select("id, abbr, baseNo", "units", "", "id in (".implode(",", array_merge($db->favUnitsSelects,$db->ingredientsSelects)).")", "");
	
	if(empty($db->favUnitsSelects)){
		$db->select("id, abbr, baseNo", "units", "", "id in (1,2,4,11,15,22,23)", "");
	}



	$_SESSION['current']['id'] = end($db->cocktails)->id;

	$thisRecipe = end($db->cocktails);

	$thisURL = 'https://myCocktailor/recipes/'.$recipeSlug.'/'.str_replace(' ', '-', $thisRecipe->name);


	//$("#overBox").addClass(linkClasses.substr(start, linkClasses.length-start));

	//end($db->cocktails)->bgColour
?>

<script>$("#overBox").removeClass().addClass("BG-<?=end($db->cocktails)->bgColour?>");</script>


<div class="overBoxTitle">
	<h1><?=$thisRecipe->name?>
	<?
	if (!empty($usrId)) {
		if ($db->exists('favCocktails', "userId='".$usrId."' && cocktailId='".$thisRecipe->id."'")) {
			$toggle = "True";
		}else{
			$toggle = "False";
		}
		echo '<a href="" id="favCocktailToggle" class="favStar'.$toggle.'"></i><a/>';
	}

	?>
	</h1>
</div>



<div id="recipeCard" class="overBoxPage">

	<ul>

	<?
	
	foreach ($db->ingredients as $key => $value) {

		?>
		<li class="ingRow">
			
			<div class="ingName">
				<label><?=$value->name?></label>
			</div>

			<div class="ingUnit">
			
			<? 
			
			if ($value->unitId > 0) {
				$conversionString = '';
			?>
				<input type="hidden" class="prevUnit" value="<?=$value->unitId?>">
				<select class="unitSelect">
					<? 
					
					foreach ($db->units as $unitkey => $unitValues) { 
						$selected = ($value->unitId == $unitkey ?'selected="selected"':'');
						if ($unitkey != 'N/A' || $unitkey != null || $unitkey!= '') {
							$conversionString .= $unitkey.":".$unitValues->baseNo.", ";
						}
							
					?>
						<option value="<?=$unitkey?>" <?=$selected?>><?=$unitValues->abbr?></option>
					<? } ?>
				</select>
			<? }else{echo'&nbsp;';}?>
			</div>

			

			<div class="ingQty">
				<input type="hidden" class="qtyVal" value="<?=$value->quantity?>">
				<label class="valOutput"><?=$value->quantity?></label>
			</div>

		</li>
		<?

	}

	?>
		<li>

			<div class="changeAllBox">
				<select id="changeAll">
					<option value="X">Change All</option>
					<? 
					foreach ($db->units as $unitkey => $unitValues) { 
					?>
						<option value="<?=$unitkey?>" ><?=$unitValues->abbr?></option>
					<? } ?>

				</select>
			</div>

		</li>

	</ul>

	<div id="method">
		<?=$thisRecipe->method?>
	</div>
	<div id="author">
		<?=$thisRecipe->author?>, <?=$thisRecipe->year?>
	</div>

	<? if($usrId == $thisRecipe->creatorId){ ?>
		
		<button id="newRecipe" value="<?=$thisRecipe->id?>"><i class="fa fa-edit"></i><span> Edit</span></button>
	
	<?}?>

	<div id="shareBox">
	<div class="shareBtn"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?=$thisURL?>" class="share-facebook"></a></div>
	<div class="shareBtn"><a target="_blank" href="https://twitter.com/home?status=Check%20out%20this%20cocktail!%20<?=$thisURL?>" class="share-twitter"></a></div>
	<div class="shareBtn"><a target="_blank" href="https://plus.google.com/share?url=<?=$thisURL?>" class="share-g-plus"></a></div>
	<!-- <div class="shareBtn"><a href="https://pinterest.com/pin/create/button/?url=<?=$thisURL?>&media=<?=$thisURL?>&description=Check%20out%20this%20cocktail!" class="share-pinterest"></a></div> -->
	<div class="shareBtn"><i id="report-cocktail"></i></div>
		
	</div>

	</div>

</div>
<script>var unitArray = {<?=trim($conversionString, ", ")?>}; console.log(unitArray);</script>