<?php
/*
Template Name: Printophout
*/


	if($_SERVER['REQUEST_METHOD']=='POST'){
		$x1 = $_POST['x1'];
		$y1 = $_POST['y1'];
		$x2 = $_POST['x2'];
		$y2 = $_POST['y2'];
		$w = $_POST['width'];
		$h = $_POST['height'];
		$prijs = $_POST['prijs_opslag'];

		
		function uploadImageFile() { // Note: GD library is required for this function

	    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	        $iWidth = (int)$_POST['width']; 
	        $iHeight = (int)$_POST['height'];
	        $iJpgQuality = 100;

	        if ($_FILES) {

	            // if no errors
	            if (! $_FILES['upload-image']['error']) {
	                if (is_uploaded_file($_FILES['upload-image']['tmp_name'])) {

	                    // new unique filename

						$sTempFileNameFirst = TEMPLATEPATH;
	                    $sTempFileNameFirstSrc = get_bloginfo('template_directory');
	                    $sTempFileNameLast = '/cache/' . md5(time().rand());
	                    $sTempFileName = $sTempFileNameFirst . $sTempFileNameLast;
	                    $sTempFileNameSrc = $sTempFileNameFirstSrc . $sTempFileNameLast;

	                    // move uploaded file into cache folder
	                    move_uploaded_file($_FILES['upload-image']['tmp_name'], $sTempFileName);

	                    // change file permission to 644
	                    @chmod($sTempFileName, 0644);

	                    if (file_exists($sTempFileName) && filesize($sTempFileName) > 0) {
	                        $aSize = getimagesize($sTempFileName); // try to obtain image info
	                        if (!$aSize) {
	                            @unlink($sTempFileName);
	                            return;
	                        }

	                        // check for image type
	                        switch($aSize[2]) {
	                            case IMAGETYPE_JPEG:
	                            case IMAGETYPE_JPEG2000:
	                                $sExt = '.jpg';
	                                // create a new image from file 
	                                $vImg = @imagecreatefromjpeg($sTempFileName);
	                                break;
	                            case IMAGETYPE_PNG:
	                                $sExt = '.png';

	                                // create a new image from file 
	                                $vImg = @imagecreatefrompng($sTempFileName);
	                                break;
	                            default:
	                                @unlink($sTempFileName);
	                                echo("unlink");
	                                return;
	                        }
	                        // create a new true color image
	                        $vDstImg = @imagecreatetruecolor( $iWidth, $iHeight );
	                        // copy and resize part of an image with resampling
	                        imagecopyresampled($vDstImg, $vImg, 0, 0, (int)$_POST['x1'], (int)$_POST['y1'], $iWidth, $iHeight, (int)$_POST['width'], (int)$_POST['height']);
	                        // define a result image filename
	                        $sResultFileName = $sTempFileName . $sExt;
	                        $sResultFileNameSrc = $sTempFileNameSrc . $sExt;


	                        // output image to file
	                        imagejpeg($vDstImg, $sResultFileName, $iJpgQuality);
	                        @unlink($sTempFileName);

	                        return $sResultFileNameSrc;
	                    }
	                }
	            }
	        }
	        else{
	        	echo("geen bestanden gevonden.");
	        }
	    }
	}
	$sImage = uploadImageFile();
	
	setrawcookie("afbeelding_url", $sImage, 0, "/");
	setcookie("prijs_cookie", $prijs, 0, "/");
	header("Location: http://houtismooi.nl/houtismooi.nl/refresh");
	
	//get_header(header);
	}
	
	else{
		get_header(header);

?>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.Jcrop.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.color.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/loadingoverlay.min.js"></script>
<link href='<?php echo get_stylesheet_directory_uri(); ?>/css/jquery.Jcrop.css' rel='stylesheet' type='text/css'>

<div class="container">
    
    	
    <!-- upload form -->
    <form id="upload_form" enctype="multipart/form-data" method="post">

	    <!-- 
	    Prijs opslag
	    Minimale width van de cropbox gebasseerd op select
	    Minimale height van de cropbox gebasseerd op select
	    De thumbnail width en height gebasseerd op select
	    De originele width en height van de afbeelding afgeleid van upload-image
	    De achtergrond link van de thumbnail gebasseerd op select 
	    Error label voor het weergeven van errors
	    -->
	    <input type="hidden" id="prijs-opslag" name="prijs_opslag" />
	    <input type="hidden" id="min-width" name="min-width" value="" />
	    <input type="hidden" id="min-height" name="min-height" value="" />
	    <input type="hidden" id="thumbnail-width" name="thumbnail-width" value="" />
	    <input type="hidden" id="thumbnail-height" name="thumbnail-height" value="" />
	    <input type="hidden" id="orig_width" name="orig_width" value="" />
	    <input type="hidden" id="orig_height" name="orig_height" value="" />
	    <input type="hidden" id="achtergrond-link" name="achtergrond-link" value="" />
	    

	    <!--alle crop dimensions-->
        <input type="hidden" id="x1" name="x1" />
        <input type="hidden" id="y1" name="y1" />
        <input type="hidden" id="x2" name="x2" />
        <input type="hidden" id="y2" name="y2" />
        <input type="hidden" id="w" name="width" />
        <input type="hidden" id="h" name="height" />

        <div class="crop-area row" id="crop-area-id">
        	<div class="col-md-12">
        		<h2 id="prijs-display-text">Prijs begint bij ons kleinste bord:</h2>
        		<h2 id="prijs-display">€35,00</h2>
        		<p id="kies-formaat-text">Kies hier je formaat</p>
		        <select autocomplete="off" onchange="selectOpties()" title="" class="product-opties" id="product-opties-select">
			        <option selected value="0">-- Selecteer a.u.b. --</option>
			        <option value="1" price="35">30 x 30 cm </option>
			        <option value="2" price="5">40 x 30 cm +€&nbsp;5,00</option>
			        <option value="3" price="10">40 x 40 cm +€&nbsp;10,00</option>
			        <option value="4" price="15">50 x 40 cm +€&nbsp;15,00</option>
			        <option value="5" price="20">50 x 50 cm +€&nbsp;20,00</option>
			        <option value="6" price="20">60 x 40 cm +€&nbsp;20,00</option>
			        <option value="7" price="25">60 x 50 cm +€&nbsp;25,00</option>
			        <option value="8" price="30">60 x 60 cm +€&nbsp;30,00</option>
			        <option value="9" price="30">70 x 50 cm +€&nbsp;30,00</option>
			        <option value="10" price="50">70 x 70 cm +€&nbsp;50,00</option>
			        <option value="11" price="45">80 x 60 cm +€&nbsp;45,00</option>
			        <option value="12" price="60">80 x 80 cm +€&nbsp;60,00</option>
			        <option value="13" price="50">90 x 60 cm +€&nbsp;50,00</option>
			        <option value="14" price="70">90 x 90 cm +€&nbsp;70,00</option>
			        <option value="15" price="60">100 x 70 cm +€&nbsp;60,00</option>
			        <option value="16" price="80">100 x 100 cm +€&nbsp;80,00</option>
			        <option value="17" price="100">110 x 110 cm +€&nbsp;100,00</option>
			        <option value="18" price="45">120 x 40 cm +€&nbsp;45,00</option>
			        <option value="19" price="60">120 x 60 cm +€&nbsp;60,00</option>
			        <option value="20" price="65">120 x 80 cm +€&nbsp;65,00</option>
			        <option value="21" price="65">140 x 70 cm  +€&nbsp;65,00</option>
			        <option value="22" price="145">200 x 80 cm +€&nbsp;145,00</option>
			    </select>
			    <hr id="hr-line">

			    <div class="row">
			    	<div class="col-md-6">
					    <p id="kies-afbeelding-text">Kies hier je afbeelding</p><br id="br_html"/>
			        	<input type="file" name="upload-image" id="upload-image"/>
			        	<input type="button" value="Kies afbeelding" id="image_event" onclick="document.getElementById('upload-image').click();" /><br id="br_html1"/>
			        	<div id="select-error-label"></div>
			        	<p id="bestandtypes-text">Toegestange bestandtypes: <b>JPG, JPEG, PNG, TIF</b></p><br id="br_html2"/>
			        	<img id="preview">
			        </div>
			        <div class="col-md-6">
			        	<div class="thumbnail-div" id="thumbnail-div">
			        		<img id="thumbnail">
			        	</div>
			        	<h2 id="vvda">Voorbeeld van de afbeelding</h2>
			        </div>
		        </div>
	        </div>
        </div>
        <input type="submit" id="submit_form_button" value="In winkelwagen"></input>
    </form>
</div>

    <script type="text/javascript">
    	var jcrop_api;
    	//de functie wacht tot de upload van een image in upload-image element en checkt de width x height.
    	$(document).ready(function(){
    		var upload = document.getElementById('upload-image');
	    	upload.addEventListener("change", function(){
	    		fileChecker();
			});
	    });


	    function fileChecker(){
	    	var upload = document.getElementById('upload-image');
	    	$.LoadingOverlay("show" , { size : "80%" });
	    	var minWidth = document.getElementById('min-width').value;
    		var minHeight = document.getElementById('min-height').value;
	    	var fr = new FileReader;
			fr.onload = function(e) {
			    var img = new Image;
			    img.onload = function() {
			        //alert(img.width + " x " + img.height);
			        if(img.width > minWidth && img.height > minHeight){
			        	//afbeelding dimensions groot genoeg
			        	document.getElementById('select-error-label').innerHTML = "";
			        	document.getElementById('orig_width').value = img.width;
			        	document.getElementById('orig_height').value = img.height;
			        	$('#preview').attr('src', e.target.result);
			        	$('#thumbnail').attr('src', e.target.result);
			        	fileUploadHandler();
			        }
			        else{
			        	document.getElementById('select-error-label').innerHTML = "Afbeelding resolutie te klein, Upload een grotere afbeelding of selecteer een andere groote aub.";
			        	$.LoadingOverlay("hide");
			        	
			        	
			        }
			    };
				img.src = fr.result;
			};
			fr.readAsDataURL(upload.files[0]);
	    }

    	function fileUploadHandler(){
    		$("#image_event").css("display", "none");
    		$("#kies-afbeelding-text").css("display", "none");
    		$("#bestandtypes-text").css("display", "none");
    		$("#br_html").css("display", "none");
    		$("#br_html1").css("display", "none");
    		$("#br_html2").css("display", "none");
    		$("#vvda").css("display", "block");
    		//variabelen ophalen voor minimale hoogte en breedte gebasseerd op de geselecteerde optie LxB
    		var minWidth = document.getElementById('min-width').value;
    		var minHeight = document.getElementById('min-height').value;
    		var origWidth = document.getElementById('orig_width').value;
			var origHeight = document.getElementById('orig_height').value;
			var thumnailWidth = document.getElementById('thumbnail-width').value;
			var thumbnailHeight = document.getElementById('thumbnail-height').value;
			var achtergrondLink = document.getElementById('achtergrond-link').value;

			//check of jcrop als aanwezig is, zo ja? dan verwijderen.
			if (typeof jcrop_api != 'undefined') 
			{
	            jcrop_api.destroy();
	            jcrop_api = null;
	        }

	    	//jquery om coordinaten te updaten en thumbnail aan te passen (Jcrop)
	    	$(document).ready(function(){
		    		$('#preview').Jcrop({
		    			onChange: function(coords){showPreview(coords, origWidth, origHeight, thumnailWidth, thumbnailHeight)},
		    			onSelect: function(coords){showPreview(coords, origWidth, origHeight, thumnailWidth, thumbnailHeight)},
		    			onRelease: function(){releaseCheck(minWidth, minHeight)},
		    			boxWidth: 400,
		    			bgColor: 'white',
		    			allowSelect: 'false',
		    			setSelect: [0, 0, minWidth, minHeight],
		    			minSize: [ minWidth, minHeight ],
		    			aspectRatio: minWidth/minHeight
					}, function(){
						jcrop_api = this;
					});
				$('#thumbnail-div').css({"width" : thumnailWidth, "height" : thumbnailHeight, "background-image" : "url(" + achtergrondLink + ")"});
				$('#thumbnail').css({"opacity" : "0.7"});
			});
	    	$.LoadingOverlay("hide");
	    	$('#submit_form_button').css({"display" : "block"});
    	}

    	function showPreview(c, origWidth, origHeight, thumnailWidth, thumbnailHeight){
    		$('#x1').val(c.x);
			$('#y1').val(c.y);
			$('#x2').val(c.x2);
			$('#y2').val(c.y2);
			$('#w').val(c.w);
			$('#h').val(c.h);

			var rx = thumnailWidth / c.w;
			var ry = thumbnailHeight / c.h;

			$('#thumbnail').css({
				width: Math.round(rx * origWidth) + 'px',
				height: Math.round(ry * origHeight) + 'px',
				marginLeft: '-' + Math.round(rx * c.x) + 'px',
				marginTop: '-' + Math.round(ry * c.y) + 'px'
			});
    	}

    	function releaseCheck(mw, mh) {
			this.setOptions({ setSelect: [0,0,mw,mh] });
		}; 

    	//optie geselecteerd in de select element, Prijs verhogen of verlagen en achtergrond voorbeeld veranderen.
    	//prijs aan input field toevoegen om zo deze gegevens door te sturen naar een php script evenals de minimale pixels en aspect ratio. 
    	function selectOpties(){
    		
    		var orginelePrijs = 35;
    		var nieuwePrijs;

    		var e = document.getElementById("product-opties-select");
			var strPrijsValue = e.options[e.selectedIndex].value;

			var intStrPrijsValue = parseInt(strPrijsValue);
    		switch(intStrPrijsValue) {
    			case 0:
    				nieuwePrijs = orginelePrijs;
    				document.getElementById('select-error-label').innerHTML = "selecteer een optie aub.";
			    case 1:
			        nieuwePrijs = orginelePrijs;
			        document.getElementById("min-width").value = "603";
			        document.getElementById('min-height').value = "582";
			        document.getElementById('thumbnail-width').value = "300";
			        document.getElementById('thumbnail-height').value = "290";
			        document.getElementById('achtergrond-link').value = "http://houtismooi.nl/houtismooi.nl/wp-content/themes/vantage/poh-img/30x30-cm.png";
			        break;
			    case 2:
			        nieuwePrijs = orginelePrijs + 5
			        document.getElementById("min-width").value = "807";
			        document.getElementById('min-height').value = "585";
			        document.getElementById('thumbnail-width').value = "300";
			        document.getElementById('thumbnail-height').value = "218";
			        document.getElementById('achtergrond-link').value = "http://houtismooi.nl/houtismooi.nl/wp-content/themes/vantage/poh-img/40x30-cm.png";
			        break;
			    case 3:
			        nieuwePrijs = orginelePrijs + 10
			        document.getElementById('min-width').value = "807";
			        document.getElementById('min-height').value = "776";
			        document.getElementById('thumbnail-width').value = "300";
			        document.getElementById('thumbnail-height').value = "288";
			        document.getElementById('achtergrond-link').value = "http://houtismooi.nl/houtismooi.nl/wp-content/themes/vantage/poh-img/40x40-cm.png";
			        break;
			    case 4:
			        nieuwePrijs = orginelePrijs + 15
			        document.getElementById('min-width').value = "1003";
			        document.getElementById('min-height').value = "772";
			        document.getElementById('thumbnail-width').value = "300";
			        document.getElementById('thumbnail-height').value = "231";
			        document.getElementById('achtergrond-link').value = "http://houtismooi.nl/houtismooi.nl/wp-content/themes/vantage/poh-img/50x40-cm.png";
			        break;
			    case 5:
			        nieuwePrijs = orginelePrijs + 20
			        document.getElementById('min-width').value = "1003";
			        document.getElementById('min-height').value = "972";
			        document.getElementById('thumbnail-width').value = "300";
			        document.getElementById('thumbnail-height').value = "291";
			        document.getElementById('achtergrond-link').value = "http://houtismooi.nl/houtismooi.nl/wp-content/themes/vantage/poh-img/50x50-cm.png";
			        break;
			    case 6:
			        nieuwePrijs = orginelePrijs + 20
			        document.getElementById('min-width').value = "1207";
			        document.getElementById('min-height').value = "774";
			        document.getElementById('thumbnail-width').value = "300";
			        document.getElementById('thumbnail-height').value = "193";
			        document.getElementById('achtergrond-link').value = "http://houtismooi.nl/houtismooi.nl/wp-content/themes/vantage/poh-img/60x40-cm.png";
			        break;
			    case 7:
			        nieuwePrijs = orginelePrijs + 25
			        document.getElementById('min-width').value = "1207";
			        document.getElementById('min-height').value = "975";
			        document.getElementById('thumbnail-width').value = "300";
			        document.getElementById('thumbnail-height').value = "243";
			        document.getElementById('achtergrond-link').value = "http://houtismooi.nl/houtismooi.nl/wp-content/themes/vantage/poh-img/60x50-cm.png";
			        break;
			    case 8:
			        nieuwePrijs = orginelePrijs + 30
			        document.getElementById('min-width').value = "1207";
			        document.getElementById('min-height').value = "1166";
			        document.getElementById('thumbnail-width').value = "300";
			        document.getElementById('thumbnail-height').value = "290";
			        document.getElementById('achtergrond-link').value = "http://houtismooi.nl/houtismooi.nl/wp-content/themes/vantage/poh-img/60x60-cm.png";
			        break;
			    case 9:
			        nieuwePrijs = orginelePrijs + 30
			        document.getElementById('min-width').value = "1403";
			        document.getElementById('min-height').value = "972";
			        document.getElementById('thumbnail-width').value = "300";
			        document.getElementById('thumbnail-height').value = "208";
			        document.getElementById('achtergrond-link').value = "http://houtismooi.nl/houtismooi.nl/wp-content/themes/vantage/poh-img/70x50-cm.png";
			        break;
			    case 10:
			        nieuwePrijs = orginelePrijs + 50
			        document.getElementById('min-width').value = "1403";
			        document.getElementById('min-height').value = "1362";
			        document.getElementById('thumbnail-width').value = "300";
			        document.getElementById('thumbnail-height').value = "291";
			        document.getElementById('achtergrond-link').value = "http://houtismooi.nl/houtismooi.nl/wp-content/themes/vantage/poh-img/70x70-cm.png";
			        break;
			    case 11:
			        nieuwePrijs = orginelePrijs + 45
			        document.getElementById('min-width').value = "1607";
			        document.getElementById('min-height').value = "1165";
			        document.getElementById('thumbnail-width').value = "300";
			        document.getElementById('thumbnail-height').value = "218";
			        document.getElementById('achtergrond-link').value = "http://houtismooi.nl/houtismooi.nl/wp-content/themes/vantage/poh-img/80x60-cm.png";
			        break;
			    case 12:
			        nieuwePrijs = orginelePrijs + 60
			        document.getElementById('min-width').value = "1607";
			        document.getElementById('min-height').value = "1566";
			        document.getElementById('thumbnail-width').value = "300";
			        document.getElementById('thumbnail-height').value = "292";
			        document.getElementById('achtergrond-link').value = "http://houtismooi.nl/houtismooi.nl/wp-content/themes/vantage/poh-img/80x80-cm.png";
			        break;
			    case 13:
			        nieuwePrijs = orginelePrijs + 50
			        document.getElementById('min-width').value = "1803";
			        document.getElementById('min-height').value = "1161";
			        document.getElementById('thumbnail-width').value = "300";
			        document.getElementById('thumbnail-height').value = "194";
			        document.getElementById('achtergrond-link').value = "http://houtismooi.nl/houtismooi.nl/wp-content/themes/vantage/poh-img/90x60-cm.png";
			        break;
			    case 14:
			        nieuwePrijs = orginelePrijs + 70
			        document.getElementById('min-width').value = "1803";
			        document.getElementById('min-height').value = "1752";
					document.getElementById('thumbnail-width').value = "300";
			        document.getElementById('thumbnail-height').value = "291";
			        document.getElementById('achtergrond-link').value = "http://houtismooi.nl/houtismooi.nl/wp-content/themes/vantage/poh-img/90x90-cm.png";
			        break;
			    case 15:
			        nieuwePrijs = orginelePrijs + 60
			        document.getElementById('min-width').value = "2007";
			        document.getElementById('min-height').value = "1364";
			        document.getElementById('thumbnail-width').value = "300";
			        document.getElementById('thumbnail-height').value = "204";
			        document.getElementById('achtergrond-link').value = "http://houtismooi.nl/houtismooi.nl/wp-content/themes/vantage/poh-img/100x70-cm.png";
			        break;
			    case 16:
			        nieuwePrijs = orginelePrijs + 80
			        document.getElementById('min-width').value = "2007";
			        document.getElementById('min-height').value = "1943";
			        document.getElementById('thumbnail-width').value = "300";
			        document.getElementById('thumbnail-height').value = "291";
			        document.getElementById('achtergrond-link').value = "http://houtismooi.nl/houtismooi.nl/wp-content/themes/vantage/poh-img/100x100-cm.png";
			        break;
			    case 17:
			        nieuwePrijs = orginelePrijs + 100
			        document.getElementById('min-width').value = "2203";
			        document.getElementById('min-height').value = "2142";
			        document.getElementById('thumbnail-width').value = "300";
			        document.getElementById('thumbnail-height').value = "292";
			        document.getElementById('achtergrond-link').value = "http://houtismooi.nl/houtismooi.nl/wp-content/themes/vantage/poh-img/110x110-cm.png";
			        break;
			    case 18:
			        nieuwePrijs = orginelePrijs + 45
			        document.getElementById('min-width').value = "2407";
			        document.getElementById('min-height').value = "774";
			        document.getElementById('thumbnail-width').value = "300";
			        document.getElementById('thumbnail-height').value = "96";
			        document.getElementById('achtergrond-link').value = "http://houtismooi.nl/houtismooi.nl/wp-content/themes/vantage/poh-img/120x40-cm.png";
			        break;
			    case 19:
			        nieuwePrijs = orginelePrijs + 60
			        document.getElementById('min-width').value = "2407";
			        document.getElementById('min-height').value = "1163";
			        document.getElementById('thumbnail-width').value = "300";
			        document.getElementById('thumbnail-height').value = "145";
			        document.getElementById('achtergrond-link').value = "http://houtismooi.nl/houtismooi.nl/wp-content/themes/vantage/poh-img/120x60-cm.png";
			        break;
			    case 20:
			        nieuwePrijs = orginelePrijs + 65
			        document.getElementById('min-width').value = "2407";
			        document.getElementById('min-height').value = "1564";
			        document.getElementById('thumbnail-width').value = "300";
			        document.getElementById('thumbnail-height').value = "195";
			        document.getElementById('achtergrond-link').value = "http://houtismooi.nl/houtismooi.nl/wp-content/themes/vantage/poh-img/120x80-cm.png";
			        break;
			    case 21:
			        nieuwePrijs = orginelePrijs + 65
			        document.getElementById('min-width').value = "2807";
			        document.getElementById('min-height').value = "1363";
			        document.getElementById('thumbnail-width').value = "300";
			        document.getElementById('thumbnail-height').value = "146";
			        document.getElementById('achtergrond-link').value = "http://houtismooi.nl/houtismooi.nl/wp-content/themes/vantage/poh-img/140x70-cm.png";
			        break;
			    case 22:
			        nieuwePrijs = orginelePrijs + 145
			        document.getElementById('min-width').value = "4008";
			        document.getElementById('min-height').value = "1563";
			        document.getElementById('thumbnail-width').value = "300";
			        document.getElementById('thumbnail-height').value = "120";
			        document.getElementById('achtergrond-link').value = "http://houtismooi.nl/houtismooi.nl/wp-content/themes/vantage/poh-img/200x80-cm.png";
			        break;
			    default:
			         document.getElementById('select-error-label').innerHTML = "selecteer een optie aub.";
			}
			//document.getElementById("prijs-opslag").value = nieuwePrijs;
			$('#prijs-opslag').val(nieuwePrijs);
			$('#prijs-display').text("€" + nieuwePrijs + ",00");
			document.getElementById("image_event").style.display = "inline";
			document.getElementById("kies-afbeelding-text").style.display = "inline";
			document.getElementById("bestandtypes-text").style.display = "inline";
			if (typeof jcrop_api != 'undefined') 
			{
	            fileChecker();
	        }
    	}
    	
    </script>

    <style>
    	#upload_form{
    	}
    	#image_event{
    		display: none;
    		margin-bottom: 2px;
    		margin-top: 5px;
    		background: rgba(0, 140, 186, 1);
		    border: 2px solid #008CBA;
		    border-radius: 0px;
		    color: white;
		    padding: 16px 32px;
		    text-align: center;
		    text-decoration: none;
		    font-size: 16px;
		    -webkit-transition-duration: 0.4s; 
		    transition-duration: 0.4s;
		    cursor: pointer;
		    box-shadow: none;
		    text-shadow: none;
    	}
    	#image_event:hover{
    		background-color: white; 
    		color: black; 
    		border: 2px solid #008CBA;
    	}
    	#submit_form_button{
    		display: none;
    		margin-bottom: 2px;
    		margin-top: 20px;
    		background: rgba(0, 140, 186, 1);
		    border: 2px solid #008CBA;
		    border-radius: 0px;
		    color: white;
		    padding: 16px 32px;
		    text-align: center;
		    text-decoration: none;
		    font-size: 16px;
		    -webkit-transition-duration: 0.4s; 
		    transition-duration: 0.4s;
		    cursor: pointer;
		    box-shadow: none;
		    text-shadow: none;
    	}
    	#submit_form_button:hover{
    		background-color: white; 
    		color: black; 
    		border: 2px solid #008CBA;
    	}
    	#vvda{
    		display: none;
    		padding-left: 33px;
    		font-weight: bold;
    		font-size: 17px;
    		padding-top: 5px;
    	}
    	#upload-image{
    		display: none;
    	}
    	#prijs-display-text{
    		font-size: 35px;
    		padding-bottom: 5px;
    	}
    	#prijs-display{
    		font-size: 35px;
    		color: #00a9c7;
    		padding-bottom: 25px;
    	}
    	#product-opties-select{
    		height: 36px;
    		padding: 8px;
    		width: 100%;
    	}
    	#kies-formaat-text{
    		color: #333;
    		font-size: 15px;
    		font-weight: bold;
    		margin: 1px;
    	}
    	#kies-afbeelding-text{
    		color: #333;
    		font-size: 15px;
    		font-weight: bold;
    		margin: 1px;
    		display: none;
    	}
    	#bestandtypes-text{
    		color: #333;
    		font-size: 15px;
    		margin: 1px;
    		display: none;
    	}
    	#hr-line{
    		width: 100%;
    		background-color: #e4e4e4;
    	}
    	.crop-area{
    		width: auto;
    		height: auto;
    	}
    	#testImage{
    		display: none;
    	}
    	#preview{
    		float: left;
    	}
    	#thumbnail{
 
    	}
    	.thumbnail-div{
    		overflow: hidden;
    	}
    	#bgtest{
    		opacity: 0.2;
    	}
    	#select-error-label{
    		color: red;
    		font-size: 15px;
    		width: 100%;
    	}
    </style>

<?php
	}
	get_footer(footer); 
?>
