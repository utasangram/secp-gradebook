<html>

	<link href="/css/yourgrade.css" rel="stylesheet" type="text/css">

	<div class="form-style-5">
		<div class="form-style-6">
			<h1>Error</h1>
		</div>
		<form action="LogIn.html" method='POST'>
		    <h4>
			<?php
			if(isset($_GET['errormsg'])){
					//echo $_GET['errormsg'];
					echo htmlspecialchars($_GET['errormsg'], ENT_QUOTES, 'UTF-8');
				}
				else{
					echo "Something Went Wrong";
				}
			?>
			</h4>

			<input type="submit" name="login" value="Login" />
		</form>
	</div>
</html>
