<?php
//Developed by https://webappdev.my.id/

include("config.php");
include("functions.php");
include("uilang.php");

?>

<!DOCTYPE html>
<html>
	<head>
		
		<?php
		
		if(isset($_GET["post"])){
			$postid = mysqli_real_escape_string($connection, $_GET["post"]);
			$sql = "SELECT * FROM $tableposts WHERE postid = '$postid'";
			$result = mysqli_query($connection, $sql);
			if($result){
				$title = shorten_text(mysqli_fetch_assoc($result)["title"], 40, ' ...', false) . " - " . $websitetitle;
			}
			?>
			
			<?php
		}else if(isset($_GET["category"])){
			$title = urldecode($_GET["category"]) . " - " . $websitetitle;
		}else if(isset($_GET["search"])){
			$title = urldecode($_GET["search"]) . " - " . $websitetitle;
		}else{
			$title = $websitetitle;
		}
		
		?>
		
		<title><?php echo $title ?></title>
		
		<meta charset="utf-8">
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<link rel="shortcut icon" href="<?php echo $baseurl ?>favicon.ico" type="image/x-icon">
		<link rel="icon" href="<?php echo $baseurl ?>favicon.ico" type="image/x-icon">
		
		<script
          src="https://code.jquery.com/jquery-3.4.1.min.js"
          integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
          crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@300&display=swap" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="<?php echo $baseurl ?>assets/css/font-awesome.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $baseurl ?>slick/slick.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo $baseurl ?>slick/slick-theme.css"/>
        <script type="text/javascript" src="<?php echo $baseurl ?>slick/slick.min.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo $baseurl ?>sharingbuttons.css"/>
		<?php include("style.php"); ?>
		<script src="<?php echo $baseurl ?>somefunctions.js"></script>
	</head>
	<body>
		<div id="header">
			<div class="inlinecenterblock" style="padding: 10px; padding-top: 15px; padding-left: 20px; padding-right: 0px;">
				<?php
				$currentlogo = "images/logo.png";
				if($logo != "")
					$currentlogo = "pictures/" . $logo;
				?>
				<a href="<?php echo $baseurl ?>"><img src="<?php echo $baseurl . $currentlogo ?>" style="height: 48px;"></a>
			</div>
			<div class="inlinecenterblock" style="color: <?php echo $maincolor ?>; font-weight: bold;">
				<h1 style="margin: 0px; font-size: 25px;"><a href="<?php echo $baseurl ?>"><?php echo $websitetitle ?></a></h1>
			</div>
			<div class="inlinecenterblock" style="float: right;" onclick="togglesearch()">
				<div class="searchbutton"><i class="fa fa-search"></i></div>
			</div>
		</div>
		
		<?php 
		//search
		if(isset($_GET["search"])){
			$q = mysqli_real_escape_string($connection, urldecode($_GET["search"]));
			if($q != ""){
				$sql = "SELECT * FROM $tableposts WHERE title LIKE '%$q%' OR content LIKE '%$q%' ORDER BY id DESC";
				$result = mysqli_query($connection, $sql);
				?>
				<div class="section">
					<div class="catseparator">
						<div style="display: inline-block;"><h1 style="font-size: 21px;"><i class="fa fa-search" style="color: <?php echo $maincolor ?>;"></i> <?php echo uilang("Search result for") ?> "<?php echo $q ?>"</h1></div>
					</div>
				</div>
				<?php
				if(mysqli_num_rows($result) > 0){
					//result found
					?>
					<div class="section gridcontainerunscrollable">
						<?php
						while($vidrow = mysqli_fetch_assoc($result)){
							$imagefile = $vidrow["picture"];
							if($imagefile == ""){
								$imagefile = "images/defaultimg.jpg";
							}else{
								$imagefile = "pictures/" . $imagefile;
							}

							?>
							<!-- Thumbnail -->
							<div class="filmblock" style="background: url(<?php echo $baseurl . $imagefile ?>) no-repeat center center; background-size: cover; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
								<div class="filmblocktitleholder">
									
									<?php
									$saleprice = $vidrow["normalprice"];
									$oldprice = "";
									if($vidrow["discountprice"] != 0){
										$saleprice = $vidrow["discountprice"];
										$oldprice = "<span style='margin: 0px; margin-top: 20px; text-decoration: line-through; font-size: 12px; margin-right: 10px; color: gray;'>" . $currencysymbol . number_format($vidrow["normalprice"],2) . "</span>";
									}
									?>
									
									
									<h2 style="font-size: 14px;"><?php echo shorten_text($vidrow["title"], 25, ' ...', false) ?></h2><h3 style="font-size: 20px; font-weight: bold; color: <?php echo $maincolor ?>"><?php echo $oldprice . $currencysymbol . number_format($saleprice,2) ?></h3>
									<a href="<?php echo $baseurl ?>?post=<?php echo $vidrow["postid"] ?>"><div class="morebutton"><?php echo uilang("MORE") ?> <i class="fa fa-arrow-right"></i></div></a>
								</div>
							</div>
							<?php

						}
						?>
					</div>
					<?php
				}else{
					?>
					<div class="section gridcontainerunscrollable">
						<p><?php echo uilang("Nothing found") ?>.</p>
					</div>
					<?php
				}
			}
		}
		
		//all
		else if(isset($_GET["all"])){

			$sql = "SELECT * FROM $tablecategories";
			$result = mysqli_query($connection, $sql);
			if(mysqli_num_rows($result) > 0){
				$row = mysqli_fetch_assoc($result);
				$catid = $row["id"];
				$vidsql = "SELECT * FROM $tableposts WHERE catid = '$catid' ORDER BY id DESC";
				$vidresult = mysqli_query($connection, $vidsql);
				if(mysqli_num_rows($vidresult) > 0){
					?>
					<div class="section">
						<div class="catseparator">
							<div style="display: inline-block;"><h1 style="font-size: 21px;"><i class="fa fa-tag" style="color: <?php echo $maincolor ?>;"></i> <?php echo uilang("All") ?></h1></div>
						</div>
					</div>
					<div class="section gridcontainerunscrollable">
						<?php
						while($vidrow = mysqli_fetch_assoc($vidresult)){
							$imagefile = $vidrow["picture"];
							if($imagefile == ""){
								$imagefile = "images/defaultimg.jpg";
							}else{
								$imagefile = "pictures/" . $imagefile;
							}

							?>
							<!-- Thumbnail -->
							<div class="filmblock" style="background: url(<?php echo $baseurl . $imagefile ?>) no-repeat center center; background-size: cover; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
								<div class="filmblocktitleholder">
									
									<?php
									$saleprice = $vidrow["normalprice"];
									$oldprice = "";
									if($vidrow["discountprice"] != 0){
										$saleprice = $vidrow["discountprice"];
										$oldprice = "<span style='margin: 0px; margin-top: 20px; text-decoration: line-through; font-size: 12px; margin-right: 10px; color: gray;'>" . $currencysymbol . number_format($vidrow["normalprice"],2) . "</span>";
									}
									?>
									
									
									<h2 style="font-size: 14px;"><?php echo shorten_text($vidrow["title"], 25, ' ...', false) ?></h2><h3 style="font-size: 20px; font-weight: bold; color: <?php echo $maincolor ?>"><?php echo $oldprice . $currencysymbol . number_format($saleprice) ?></h3>
									<a href="<?php echo $baseurl ?>?post=<?php echo $vidrow["postid"] ?>"><div class="morebutton"><?php echo uilang("MORE") ?> <i class="fa fa-arrow-right"></i></div></a>
								</div>
							</div>
							<?php

						}
						?>
					</div>
					<?php
				}
			}
		}
		
		//category
		else if(isset($_GET["category"])){
			$category = urldecode(mysqli_real_escape_string($connection, $_GET["category"]));
			if($category != ""){
				$sql = "SELECT * FROM $tablecategories WHERE category = '$category'";
				$result = mysqli_query($connection, $sql);
				if(mysqli_num_rows($result) > 0){
					$row = mysqli_fetch_assoc($result);
					$catid = $row["id"];
					$vidsql = "SELECT * FROM $tableposts WHERE catid = '$catid' ORDER BY id DESC";
					$vidresult = mysqli_query($connection, $vidsql);
					if(mysqli_num_rows($vidresult) > 0){
						?>
						<div class="section">
							<div class="catseparator">
								<div style="display: inline-block;"><h1 style="font-size: 21px;"><i class="fa fa-tag" style="color: <?php echo $maincolor ?>;"></i> <?php echo $category ?></h1></div>
							</div>
						</div>
						<div class="section gridcontainerunscrollable">
							<?php
							while($vidrow = mysqli_fetch_assoc($vidresult)){
								$imagefile = $vidrow["picture"];
								if($imagefile == ""){
									$imagefile = "images/defaultimg.jpg";
								}else{
									$imagefile = "pictures/" . $imagefile;
								}

								?>
								<!-- Thumbnail -->
								<div class="filmblock" style="background: url(<?php echo $baseurl . $imagefile ?>) no-repeat center center; background-size: cover; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
									<div class="filmblocktitleholder">
										
										<?php
										$saleprice = $vidrow["normalprice"];
										$oldprice = "";
										if($vidrow["discountprice"] != 0){
											$saleprice = $vidrow["discountprice"];
											$oldprice = "<span style='margin: 0px; margin-top: 20px; text-decoration: line-through; font-size: 12px; margin-right: 10px; color: gray;'>" . $currencysymbol . number_format($vidrow["normalprice"],2) . "</span>";
										}
										?>
										
										
										<h2 style="font-size: 14px;"><?php echo shorten_text($vidrow["title"], 25, ' ...', false) ?></h2><h3 style="font-size: 20px; font-weight: bold; color: <?php echo $maincolor ?>"><?php echo $oldprice . $currencysymbol . number_format($saleprice) ?></h3>
										<a href="<?php echo $baseurl ?>?post=<?php echo $vidrow["postid"] ?>"><div class="morebutton"><?php echo uilang("MORE") ?> <i class="fa fa-arrow-right"></i></div></a>
									</div>
								</div>
								<?php

							}
							?>
						</div>
						<?php
					}
				}
			}
		}
		//post
		else if(isset($_GET["post"])){
			?>
			<div class="section">
				<div class="posttableblock">
					<div class="postcontent">
						<?php
						$postid = mysqli_real_escape_string($connection, $_GET["post"]);
						if($postid != ""){
							$sql = "SELECT * FROM  $tableposts WHERE postid = '$postid'";
							$result = mysqli_query($connection, $sql);
							if(mysqli_num_rows($result) == 0){
								echo "<p>" .uilang("Nothing found"). "</p>";
							}else{
								$row = mysqli_fetch_assoc($result);
								
								$picture = $row["picture"];
								
								if($picture != ""){
									$picture = $baseurl . "pictures/" . $picture;
								}else{
									$picture = $baseurl . "images/defaultimg.jpg";
								}
								
								$mil = $row["time"];
								$seconds = $mil / 1000;
								$postdate = date("d-m-Y", $seconds);
								?>
								
								<div id="productpic" style="background: url(<?php echo $picture ?>) no-repeat center center; background-size: cover; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
								
								<?php
								$saleprice = $row["normalprice"];
								$oldprice = "";
								if($row["discountprice"] != 0){
									$saleprice = $row["discountprice"];
									$oldprice = "<span style='margin: 0px; margin-top: 20px; text-decoration: line-through; font-size: 20px; margin-right: 10px; color: gray;'>" . $currencysymbol . number_format($row["normalprice"],2) . "</span>";
								}
								?>
								
								<h1><?php echo $row["title"] ?> <i class="fa fa-angle-double-right"></i> <?php echo $oldprice . $currencysymbol . number_format($saleprice,2) ?></h1>
								<h5 style="color: <?php echo $maincolor ?>"><i class="fa fa-calendar" style="width: 15px;"></i> <?php echo $postdate ?> <a href="<?php echo $baseurl ?>?category=<?php echo urlencode(showcatname($row["catid"])) ?>"><i class="fa fa-tag" style="margin-left: 15px; width: 15px;"></i> <?php echo showCatName($row["catid"]) ?></a></h5>
								<div>
									<?php echo $row["content"] ?>
								</div>
								
								<!-- Social Share Buttons-->
								<div style="font-size: 12px;">
									<?php
									showSharer($baseurl . "?post/" . $row["postid"], $websitetitle);
									?>
								</div>
								<br><br>
								
								<!-- Facebook Comments Plugin -->
								<div style="width: 100%; box-sizing: border-box; background-color: white; border-radius: 20px; padding: 14px;">
									<div id="fb-root"></div>
									<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&amp;version=v5.0&amp;appId=569420283509636&amp;autoLogAppEvents=1"></script>
									 
									<div class="fb-comments" data-href="<?php echo $baseurl ?>?post/<?php echo $row["postid"] ?>" data-width="100%"  data-numposts="14"></div>
									
								</div>
								
								
								<script>
									function viewedThis(postid){
										$.post("<?php echo $baseurl ?>viewcounter.php", {
											postid : postid
										}, function(data){
											console.log(data)
										})
									}
									//viewedThis("<?php echo $postid ?>")
								</script>
								<?php
							}
						}
						?>
					</div>
					<div class="randomvids">
						<div class="randomvidblock orderblock">
							<h2><?php echo uilang("Order") ?></h2>
							<label><i class="fa fa-plus"></i> <?php echo uilang("Quantity") ?></label>
							<input id="currentQ" type="number" value=1 onchange="updateCurrentTotal()" style="border-radius: 0px;">
							
							<?php
							if($row["options"] != ""){
								?>
								<div id="productoptions" style="display: none"><?php echo $row["options"] ?></div>
								<script>
									var moptions = JSON.parse($("#productoptions").html())
									var productoptions = "<label>" + moptions[0].title + "</label>" 
									productoptions += "<select id='productoptionsselect' onchange='overrideprice()'>"
									for(var i = 0; i < moptions[0].options.length; i++){
										productoptions += "<option value=" +moptions[0].options[i].price+ ">" + moptions[0].options[i].title + "</option>"
									}
									productoptions += "</select>"
									$("#productoptions").html(productoptions).show()
									setTimeout(function(){
										overrideprice()
									}, 1000)
								</script>
								<?php
							}
							?>
							
							<label><i class="fa fa-file-text-o"></i> <?php echo uilang("Notes") ?></label>
							<textarea id="ordernotes" placeholder="<?php echo uilang("Write some notes...") ?>" style="border-radius: 0px;"></textarea>
							<p id="currenttotal" style="font-size: 30px;">Rp. 12345</p>
							<div class="buybutton" onclick="addtocart()"><i class="fa fa-shopping-cart"></i> <?php echo uilang("Add to Cart") ?></div>
							
							<script>
								var currentprice = <?php echo $saleprice ?>;
								var currentTotal = 0
								var currentitem = {
									price : currentprice,
									title : "<?php echo $row["title"] ?>",
									quantity : 0,
									options : "",
									subtotal : 0,
									notes : "",
								}
								
								function overrideprice(){
									currentprice = $("#productoptionsselect").val()
									currentitem.options = $("#productoptionsselect option:selected").text()
									updateCurrentTotal()
								}
								
								function updateCurrentTotal(){
									var currentQ = $("#currentQ").val()
									if(currentQ > 0){
										currentTotal = currentQ * currentprice
									}else{
										$("#currentQ").val("1")
										currentQ = 1
										currentTotal = currentQ * currentprice
									}
									currentitem.quantity = currentQ
									$("#currenttotal").html("<?php echo $currencysymbol ?> " + tSep(currentTotal.toFixed(2)))
								}
								updateCurrentTotal()
								
								function addtocart(){
									currentitem.notes = $("#ordernotes").val()
									currentitem.subtotal = currentTotal
									appdata.orderitems.push(currentitem)
									savedata()
									reloadcartdata()
									loaddata()
									$([document.documentElement, document.body]).animate({
										scrollTop: $(".shoppingcart").offset().top
									}, 1000);
								}
							</script>
							
						</div>
						
						<div class="randomvidblock"><?php echo uilang("You may like:") ?></div>
						<?php
						$sql = "SELECT * FROM $tableposts ORDER BY RAND() LIMIT 5";
						$result = mysqli_query($connection, $sql);
						if(mysqli_num_rows($result) > 0){
							while($row = mysqli_fetch_assoc($result)){
								?>
								<a href="<?php echo $baseurl ?>?post=<?php echo $row["postid"] ?>">
									<div class="randomvidblock">
										<?php
										$imagefile = $row["picture"];
										if($imagefile == ""){
											$imagefile = "images/defaultimg.jpg";
										}else{
											$imagefile = "pictures/" . $imagefile;
										}
										

										$saleprice = $row["normalprice"];
										$oldprice = "";
										if($row["discountprice"] != 0){
											$saleprice = $row["discountprice"];
											$oldprice = "<span style='margin: 0px; margin-top: 20px; text-decoration: line-through; font-size: 12px; margin-right: 10px; color: gray;'>" . $currencysymbol . number_format($row["normalprice"],2) . "</span>";
										}

										
										?>
										<div class="lilimage" style="background: url(<?php echo $baseurl . $imagefile ?>) no-repeat center center; background-size: cover; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover;"></div>
										<div class="lildescr">
											<div class="shorttext" style="font-size: 18px; font-weight: bold;">
												<?php echo $row["title"] ?><br><i class="fa fa-angle-double-right"></i> <?php echo $oldprice. $currencysymbol . number_format($saleprice,2) ?>
											</div>
											<div style="padding-left: 14px;">
												<p><?php echo shorten_text(strip_tags($row["content"]), 75, ' ...', false) ?></p>
											</div>
											<div style="padding-left: 14px;">
												<p style="color: <?php echo $maincolor ?>; font-weight: bold; font-size: 12px;"><i class="fa fa-calendar" style="width: 10px;"></i> <?php echo $postdate ?> <i class="fa fa-tag" style="margin-left: 5px; width: 10px;"></i> <?php echo showCatName($row["catid"]) ?></p>
											</div>
											
										</div>
									</div>
								</a>
								<?php
							}
						}
						?>
					</div>
				</div>
			</div>
			<?php
		}
		//home
		else{
			?>
		
			<div class="section firstthreecontainer">
				<div id="firstthree">
					<?php
					$sql = "SELECT * FROM $tableposts ORDER BY id DESC LIMIT 3";
					$result = mysqli_query($connection, $sql);
					if($result){
						if(mysqli_num_rows($result) == 0){
							echo "<p>" .uilang("There is no post published"). ".</p>";
						}else{
							while($row = mysqli_fetch_assoc($result)){
								$imagefile = $row["picture"];
								if($imagefile == ""){
									$imagefile = "images/defaultimg.jpg";
								}else{
									$imagefile = "pictures/" . $imagefile;
								}
								?>
								
								<div class="firstthreeblock" style="background: url(<?php echo $baseurl . $imagefile ?>) no-repeat center center; background-size: cover; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
									<a href="<?php echo $baseurl ?>?post=<?php echo $row["postid"] ?>">
										<div class="brightonhover">
											<div class="smallinmobile w75">
												
												<?php
												$saleprice = $row["normalprice"];
												$oldprice = "";
												if($row["discountprice"] != 0){
													$saleprice = $row["discountprice"];
													$oldprice = "<span style='margin: 0px; margin-top: 20px; text-decoration: line-through; font-size: 12px; margin-right: 10px; color: ".$maincolor.";'>" . $currencysymbol . number_format($row["normalprice"],2) . "</span>";
												}
												?>
												
												<h2><?php echo shorten_text($row["title"], 21, ' ...', true) . " <i class='fa fa-angle-double-right'></i> " . $oldprice . $currencysymbol . number_format($saleprice, 2) ?></h2>
												<p><?php echo shorten_text(strip_tags($row["content"]), 75, ' ...', false) ?></p>
											</div>
											<div class="smallinmobile w25" style="vertical-align: middle; text-align: center;">
												<div class="morebutton"><?php echo uilang("MORE") ?> <i class="fa fa-chevron-right" style="width: 30px;"></i></div>
											</div>
										</div>
									</a>
								</div>
								<?php
							}
						}
					}
					
					?>
				</div>
			</div>
			
			<?php 
			$sql = "SELECT * FROM $tablecategories ORDER BY category ASC";
			$result = mysqli_query($connection, $sql);
			if($result){
				if(mysqli_num_rows($result) > 0){
					while($row = mysqli_fetch_assoc($result)){
						$catid = $row["id"];
						$category = $row["category"];
						$vidsql = "SELECT * FROM $tableposts WHERE catid = '$catid' ORDER BY id DESC LIMIT 10";
						$vidresult = mysqli_query($connection, $vidsql);
						if(mysqli_num_rows($vidresult) > 0){
							?>
							<div class="section">
								<div class="catseparator">
									<div style="display: inline-block;"><h1 style="font-size: 25px;"><i class="fa fa-tag" style="color: <?php echo $maincolor ?>;"></i> <?php echo $category ?></h1></div>
									<div style="display: inline-block; float: right; margin-top: 8px; color: <?php echo $maincolor ?>;"><a class="moreoncat" href="<?php echo $baseurl ?>?category=<?php echo urlencode($category) ?>"><?php echo uilang("More in") . " " . $category ?> <i class="fa fa-plus-circle"></i></a></div>
								</div>
							</div>
							<div class="section gridcontainer">
								<?php
								while($vidrow = mysqli_fetch_assoc($vidresult)){
									$imagefile = $vidrow["picture"];
									if($imagefile == ""){
										$imagefile = "images/defaultimg.jpg";
									}else{
										$imagefile = "pictures/" . $imagefile;
									}

									?>
									
									<!-- Thumbnail -->
									<div class="filmblock" style="background: url(<?php echo $baseurl . $imagefile ?>) no-repeat center center; background-size: cover; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover;">
										<div class="filmblocktitleholder">
											
											<?php
											$saleprice = $vidrow["normalprice"];
											$oldprice = "";
											if($vidrow["discountprice"] != 0){
												$saleprice = $vidrow["discountprice"];
												$oldprice = "<span style='margin: 0px; margin-top: 20px; text-decoration: line-through; font-size: 12px; margin-right: 10px; color: gray;'>" . $currencysymbol . number_format($vidrow["normalprice"], 2) . "</span>";
											}
											?>
											
											
											<h2 style="font-size: 14px;"><?php echo shorten_text($vidrow["title"], 25, ' ...', false) ?></h2><h3 style="font-size: 20px; font-weight: bold; color: <?php echo $maincolor ?>"><?php echo $oldprice . $currencysymbol . number_format($saleprice,2) ?></h3>
											<a href="<?php echo $baseurl ?>?post=<?php echo $vidrow["postid"] ?>"><div class="morebutton"><?php echo uilang("MORE") ?> <i class="fa fa-arrow-right"></i></div></a>
										</div>
									</div>
									
									<?php

								}
								?>
							</div>
							<?php
						}
					}
				}
			}else{
				?>
				<script>
					alert("WELCOME!\nClick OK to start.\nIf this message appears again, please check that you have correct database connection.")
					location.reload()
				</script>
				<?php
			}
			
		}
		
		?>
		
		<!-- Shopping Cart -->
		<div class="section shoppingcart">
			<div>
				<h1 align="center" style="margin: 50px;"><i class="fa fa-shopping-cart"></i> <?php echo uilang("Shopping Cart") ?></h1>
			</div>
			<div class="footerlink" style="background-color: inherit;">
				<div class="flblock smallerinput">
					<h2><i class="fa fa-user"></i> <?php echo uilang("Contact Information") ?></h2>
					<label><?php echo uilang("Name") ?></label>
					<input id="biname" onkeyup="savebuyerinfo()" placeholder="<?php echo uilang("Name") ?>" type="text">
					<label><?php echo uilang("Email") ?></label>
					<input id="biemail" onkeyup="savebuyerinfo()" placeholder="<?php echo uilang("Email") ?>" type="email">
					<label><?php echo uilang("Mobile") ?></label>
					<input id="bimobile" onkeyup="savebuyerinfo()" placeholder="<?php echo uilang("Mobile") ?>" type="number">
				</div>
				<div class="flblock">
					<h2><i class="fa fa-list"></i> <?php echo uilang("Order Items") ?></h2>
					<div id="orderedproducts"><p><?php echo uilang("You did not add any product.") ?></p></div>
				</div>
				<div class="flblock">
					<h2><i class="fa fa-credit-card"></i> <?php echo uilang("Total") ?></h2>
					<p id="grandtotal" style="font-size: 30px">Rp. 0</p>
					<div class="buybutton" style="border: 1px solid white" onclick="chatnow()"><i class="fa fa-whatsapp"></i> <?php echo uilang("Order Now") ?></div>
				</div>
			</div>
			
			<script>
				//Count current grand total 
			</script>
		</div>
		
		<!-- Footer -->
		<div class="section footerlink">
		
			<div class="flblock">
				
				<h3><?php echo uilang("About") . " " . $websitetitle ?></h3>
				<?php echo $about ?>
			</div>
			
			<div class="flblock">
				<h3><?php echo uilang("Recently Published") ?></h3>
				<?php
				$sql = "SELECT * FROM $tableposts ORDER BY id DESC LIMIT 12";
				$result = mysqli_query($connection, $sql);
				if($result){
					if(mysqli_num_rows($result) > 0){
						echo "<ul>";
						while($row = mysqli_fetch_assoc($result)){
							?>
							<li><a href="<?php echo $baseurl ?>?post=<?php echo $row["postid"] ?>"><i class="fa fa-circle" style="color: <?php echo $maincolor ?>; width: 20px;"></i> <?php echo $row["title"] ?></a></li>
							<?php
						}
						echo "</ul>";
					}else{
						echo "<p>" . uilang("There is no post published") . ".</p>";
					}
				}
				
				?>
			</div>
			
			<div class="flblock">
				<h3><?php echo uilang("Categories") ?></h3>
				<?php
				$sql = "SELECT * FROM $tablecategories ORDER BY category ASC";
				$result = mysqli_query($connection, $sql);
				if($result){
					if(mysqli_num_rows($result) > 0){
						?>
						<div class="categoryblock"><a href="<?php echo $baseurl ?>?all"><i class="fa fa-tag" style="width: 10px;"></i> <?php echo uilang("All") ?></a></div>
						<?php
						while($row = mysqli_fetch_assoc($result)){
							?>
							<div class="categoryblock"><a href="<?php echo $baseurl ?>?category=<?php echo urlencode($row["category"]) ?>"><i class="fa fa-tag" style="width: 10px;"></i> <?php echo $row["category"] ?></a></div>
							<?php
						}
					}else{
						echo "<p>" . uilang("There is no category published.") . "</p>";
					}
				}
				
				?>
			</div>
			
	
			
		</div>
		
		<div class="section footercopyright">
			<span>© <?php echo date("Y"); ?> <?php echo $websitetitle; ?>. All rights reserved.</span>
		</div>
		
		
		<div id="searchui">
			<div class="sinputcontainer">
				<h3 style="color: white;"><i class="fa fa-search"></i> <?php echo uilang("Search") ?></h3>
				<input type="text" id="searchinput" placeholder="<?php echo uilang("Type something...") ?>" onkeyup="startsearch()">
			</div>
		</div>
		
		<script>
			$(document).ready(function(){
                $('#firstthree').slick({
                    autoplaySpeed: 3000,
                    autoplay : true,
                    infinite: true,
                });
            })
			
			function togglesearch(){
				$("#searchui").fadeToggle()
				$("#searchinput").focus()
			}
			
			var searchtimeout
			function startsearch(){
				clearTimeout(searchtimeout)
				searchtimeout = setTimeout(function(){
					location.href = "<?php echo $baseurl ?>?search=" + encodeURI($("#searchinput").val())
				}, 2000)
			}
				
			function addslashes(string) {
				return string.replace(/\\/g, '\\\\').
					replace(/\u0008/g, '\\b').
					replace(/\t/g, '\\t').
					replace(/\n/g, '\\n').
					replace(/\f/g, '\\f').
					replace(/\r/g, '\\r').
					replace(/'/g, '\\\'').
					replace(/"/g, '\\"');
			}
			
			var appdata = {
				buyerinfo : {
					name : "", email : "", mobile : ""
				}, 
				orderitems : []
			}
			
			if(localStorage.getItem("<?php echo $websitetitle ?>") === null){
				savedata()
			}else{
				loaddata()
			}
			
			function loaddata(){
				appdata = JSON.parse(localStorage.getItem("<?php echo $websitetitle ?>"))
			}

			
			function savedata(){
				localStorage.setItem("<?php echo $websitetitle ?>", JSON.stringify(appdata))
			}
			
			loaddata()
			
			function savebuyerinfo(){
				appdata.buyerinfo.name = $("#biname").val()
				appdata.buyerinfo.email = $("#biemail").val()
				appdata.buyerinfo.mobile = $("#bimobile").val()
				savedata()
			}
			
			function reloadcartdata(){
				$("#biname").val(appdata.buyerinfo.name)
				$("#biemail").val(appdata.buyerinfo.email)
				$("#bimobile").val(appdata.buyerinfo.mobile)
				if(appdata.orderitems.length > 0){
					
					var ordermessage = ""
					var grandtotal = 0
					for(var i = 0; i < appdata.orderitems.length; i++){
						ordermessage += "<div class='ordereditem'><div>" + appdata.orderitems[i].title + " * " + appdata.orderitems[i].quantity + " = <?php echo $currencysymbol ?>" + tSep(appdata.orderitems[i].subtotal.toFixed(2)) + "<div onclick='deleteorderitem(" +i+ ")' style='display: inline-block; margin-left: 10px; cursor: pointer;'>[<i class='fa fa-trash'></i> <?php echo uilang("Delete") ?>]</div></div>"
						
						if(appdata.orderitems[i].options != "")
							ordermessage += "<div style='margin-top: 5px; font-size: 10px;'><i class='fa fa-check-square-o' style='width: 10px;'></i> " + appdata.orderitems[i].options + "</div>"
						if(appdata.orderitems[i].notes != "")
							ordermessage += "<div style='margin-top: 5px; font-size: 10px;'><i class='fa fa-file-text-o' style='width: 10px;'></i> " + appdata.orderitems[i].notes + "</div>"
						
						ordermessage += "</div>"
						grandtotal += appdata.orderitems[i].subtotal
					}
					$("#grandtotal").html("<?php echo $currencysymbol ?>" + tSep(grandtotal.toFixed(2)))
					$("#orderedproducts").html(ordermessage)
				}else{
					$("#orderedproducts").html("<p><?php echo uilang("You did not add any product.") ?></p>")
					$("#grandtotal").html("<?php echo $currencysymbol ?>" + 0)
				}
			}
			
			function deleteorderitem(i){
				appdata.orderitems.splice(i, 1)
				savedata()
				reloadcartdata()
			}
			
			reloadcartdata()
			
			
			function chatnow(){
				if(appdata.orderitems.length){
					var message = "<?php echo uilang("Hi") . " " . $websitetitle . "," ?> "
					var grandtotal = 0
					message += "<?php echo uilang("I would like to order:") ?>\n\n"
					for(var i = 0; i < appdata.orderitems.length; i++){
						message += "-\> " + appdata.orderitems[i].title + " * " + appdata.orderitems[i].quantity + " = <?php echo $currencysymbol ?>" + tSep(appdata.orderitems[i].subtotal.toFixed(2)) + "\n"
							
						if(appdata.orderitems[i].options != "")
							message += "---\>\> <?php echo uilang("Option") ?>: " + appdata.orderitems[i].options + "\n"
						if(appdata.orderitems[i].notes != "")
							message += "---\>\> <?php echo uilang("Notes") ?>: " + appdata.orderitems[i].notes + "\n"
						grandtotal += appdata.orderitems[i].subtotal
						message += "\n"
					}
					message += "Grand Total: <?php echo $currencysymbol ?>" + tSep(grandtotal.toFixed(2)) + "\n\n<?php echo uilang("Thank you.") ?>"
					var omuri = encodeURI(message)
					$.post("<?php echo $baseurl ?>ordernotes.php", {
						"message" : message + "\n\nFrom " + appdata.buyerinfo.name + " - " + appdata.buyerinfo.email + " - " + appdata.buyerinfo.mobile
					}, function(data){
						//alert(data)
						location.href = "https://wa.me/<?php echo $adminwhatsapp ?>?text=" + omuri
					})
				}else{
					alert("<?php echo uilang("You did not add any product.") ?>")
				}
			}
			
		</script>
	</body>
</html>