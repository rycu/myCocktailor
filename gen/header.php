	<header>
		
		

		<div id="titleBox">
			<img alt="my Cocktailor" src="<?=$setup->getSiteRoot();?>img/myCocktailorLogo.svg" onerror="this.onerror=null; this.src='<?=$setup->getSiteRoot();?>img/myCocktailorLogo.png'">
			<h1><?=Setup::siteName?></h1>
		</div>


		<div id="gatewayBox">
		
		<? if(!$loggedIn){  ?>
			<div id="loginform">

				<form id="login" method="post" action="./" onsubmit="return gateway('login', this);">

					<div id="loginPane">
		
						<div class="inputBox">
							<input class="inputField" autocapitalize="off" type="text" id="email" name="email" tabindex="1"  value="" />
							<label class="innerLabel" for="email">email</label>
							<a id="emailForgot" class="forgotBtn"><i class="fa fa-question-circle"></i></a>
						</div>

						<div class="inputBox">
							<input class="inputField" type="password" id="password" name="password" tabindex="2" value="" />
							<label class="innerLabel" for="password">password</label>
							<a id="pswdForgot" class="forgotBtn"><i class="fa fa-question-circle"></i></a>
						</div>
					
					</div>
					<button id="loginBtn" type="submit"><i class="fa fa-sign-in"></i><span> Log in</span></button>
				
				</form>
			</div>

			<div id="signUpform">

				<form id="signUp" method="post" class="basicForm" action="./<?=$pageRef?>" onsubmit="return gateway('signUp', this);">
					
					<fieldset id="signUpPane">
						<legend>create &#183; share &#183; collect</legend>
						
						<div class="formRow">
							<label for="username">username:</label>
							<input class="inputField" type="text" name="username" value="">
						</div>

						<div class="formRow">
							<label for="email">email:</label>
							<input class="inputField" type="email" name="email" value="">
						</div>

						<div class="formRow">
							<label for="password">password:</label>
							<input class="inputField" type="password" name="newPassword" value="">
						</div>

						<div class="formRow">
							<p>By signing up, you agree to the <a href="./terms">Terms of use</a> and confim that you <strong>above</strong> the legal drinking age in your country of residence.</p>
						</div>

					</fieldset>
					<button id="signUpBtn" type="submit"><i class="fa fa-users"></i><span> sign up</span></button>
				</form>

			</div>

		<? }else{ ?>

			<div id="userBtns">

				<button id="accInfo" type="submit"><i class="fa fa-user"></i><span><?=$_SESSION['customs']['username']?></span></button>	
				
				<button id="favs" type="submit"><i class="fa fa-star"></i><span>Favourites</span></button>	
				
				<button id="newRecipe" type="submit"><i class="fa fa-plus"></i><span>Add New Cocktail</span></button>	

				<form method="post" action="<?=rootPath?>">
					<input type="hidden" name="task" value="logout" />
					<button type="submit"><i class="fa fa-sign-out"></i><span>Sign out</span></button>	
				</form>

			</div>

			<? } ?>

		
	</div>

	</header>