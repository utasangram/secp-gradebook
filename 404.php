<html>
    
    <link href="/css/yourgrade.css" rel="stylesheet" type="text/css">
    
    <div class="form-style-5">
        <div class="form-style-6">
        <h1>Error</h1>
        </div>
			<form>
			<?php
			if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
			$refuri = parse_url($_SERVER['HTTP_REFERER']); // use the parse_url() function to create an array containing information about the domain
			if($refuri['host'] == "team5secureprog.000webhostapp.com"){
			echo "Please contact team5secureprog@gmail.com and let them know they have a dead link to this site.";
			}
			else{
			echo "You should email someone over at " . $refuri['host'] . " and let them know they have a dead link to this site.";
			}
			}
			else{
			echo "If you got here from Angola, you took a wrong turn at Catumbela. We take secure programming seriously and if you got here by typing randomly in the address bar, stop doing that.";
			}
			?>
        </form>
	</div>    
    
</html>