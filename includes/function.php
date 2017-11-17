

<style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
</style>

<?php



function reg_user()
{
	
	$servername = "localhost";
	$username = "root";
	$password = "";
	try{
    $dbh = new pdo( "mysql:host=localhost;dbname=lazzypropertiesdb",
                    $username,
                    $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $ex){
		echo 'Connection failed: ' . $ex->getmessage();
	}
	
	if (isset($_POST['reg_submit'])) 
			{				
				try{
					//Insert User Details
					$stmt = $dbh->prepare("INSERT INTO User (email, first_name, last_name, password) 
											VALUES (:email, :firstname, :lastname, :password)");
					$stmt->bindParam(':email', $email);
					$stmt->bindParam(':firstname', $firstname);
					$stmt->bindParam(':lastname', $lastname);
					$stmt->bindParam(':password', $password);
					$email=$_POST['reg_email'];
					$lastname=$_POST['reg_lname'];
					$firstname=$_POST['reg_fname'];
					$password=$_POST['reg_password'];
					$stmt->execute();
					//Check last ID
					$stmt = $dbh->prepare("SELECT LAST_INSERT_ID() FROM User");
					$stmt->execute();
					$result = $stmt->fetchColumn();
					//Insert Contact Details
					$stmt = $dbh->prepare("INSERT INTO User_Contact (User_ID, Mobile, Email) 
											VALUES (:user_id, :mobile, :email)");
					$stmt->bindParam(':user_id', $result);
					$stmt->bindParam(':mobile', $mobile);
					$stmt->bindParam(':email', $email);
					$mobile=$_POST['reg_mobile'];
					$stmt->execute();
					echo "Register Successful!";
					//echo "<script> location.href = 'index.php' </script>";
					//echo "Error Code: " . $stmt->errorCode();
				}
				catch(PDOException $e){
					if($e->getCode() === '23000')
					{
						echo "Email already taken.";
					}
					else
					{
					echo "Error: " . $e->getMessage();
					}
				}
			}


}

function login(){
	$servername = "localhost";
	$username = "root";
	$password = "";
	
	try{
    $dbh = new pdo( "mysql:host=localhost;dbname=lazzypropertiesdb",
                    $username,
                    $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $ex){
		echo 'Connection failed: ' . $ex->getmessage();
	}
	
	//global $dbh
	if (isset($_POST['login_submit'])) 
	{			
		try{
			//Select User With Same Email && Pass
			$stmt = $dbh->prepare("SELECT * FROM User WHERE Email=:email && Password=:password");
			$stmt->bindParam(':email', $email);
			$stmt->bindParam(':password', $password);
			$email=$_POST['login_email'];
			$password=$_POST['login_password'];
			$stmt->execute();
			$count = (int)$stmt->rowCount();
			if($count == 1 ){
				echo "Log In Successful!";
				$result = $stmt->fetch(PDO::FETCH_OBJ);
				$_SESSION['ID'] = $result->User_ID;
				$_SESSION['Email'] = $result->Email;
				$_SESSION['FName'] = $result->First_Name;
				$_SESSION['LName'] = $result->Last_Name;
			}
			else if($count > 1){
				echo "Error occured. Please contact the administrator.";
			}
			else {
				echo "Incorrect email or password.";
			}
			// Fetch data from query
			
			//Start PHP Session
			//$_SESSION['ID'] = $result->
			//echo "Log In Successful!";
			//echo "<script> location.href = 'index.php' </script>";
		}
		catch(PDOException $e){
			echo "Error: " . $e->getMessage();
		}
	}
}

function post_property(){
	
	$servername = "localhost";
	$username = "root";
	$password = "";
	try{
    $dbh = new pdo( "mysql:host=localhost;dbname=lazzypropertiesdb",
                    $username,
                    $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $ex){
		echo 'Connection failed: ' . $ex->getmessage();
	}
	
	if (isset($_POST['post_submit'])) 
	{				
		try{
			//Insert User Details
			$stmt = $dbh->prepare("INSERT INTO Property (Title, Type, Price, Description, User_ID) 
									VALUES (:title, :type, :price, :description, :user_id)");
			$stmt->bindParam(':title', $title);
			$stmt->bindParam(':type', $type);
			$stmt->bindParam(':price', $price);
			$stmt->bindParam(':description', $description);
			$stmt->bindParam(':user_id',$user_id);
			$title = $_POST['post_title'];
			$type = $_POST['post_type'];
			$price = $_POST['post_price'];
			$description = $_POST['post_description'];
			$user_id = $_SESSION['ID'];
			$stmt->execute();
			//Check last ID
			$stmt = $dbh->prepare("SELECT LAST_INSERT_ID() FROM Property");
			$stmt->execute();
			$result = $stmt->fetchColumn();
			//Insert Contact Details
			$stmt = $dbh->prepare("INSERT INTO Property_Location (Property_ID, Country, Zip, State, City, StreetAddress) 
									VALUES (:property_id, :country, :zip, :state, :city, :streetaddress)");
			$stmt->bindParam(':property_id', $result);
			$stmt->bindParam(':country', $country);
			$stmt->bindParam(':zip', $zip);
			$stmt->bindParam(':state', $state);
			$stmt->bindParam('city', $city);
			$stmt->bindParam(':streetaddress', $route);
			$country = $_POST['post_country'];
			$zip = $_POST['post_zip'];
			$state = $_POST['post_state'];
			$city = $_POST['post_city'];
			$route = $_POST['post_route'];
			$stmt->execute();
			$stmt = $dbh->prepare("INSERT INTO Property_Features (Property_ID, Stories, Bed, Bath, Garage) 
									VALUES (:property_id, :stories, :bed, :bath, :garage)");
			$stmt->bindParam(':property_id', $result);
			$stmt->bindParam(':stories', $stories);
			$stmt->bindParam(':bed', $bed);
			$stmt->bindParam(':bath', $bath);
			$stmt->bindParam(':garage', $garage);
			$stories = $_POST['post_stories'];
			$bed = $_POST['post_bed'];
			$bath = $_POST['post_bath'];
			$garage = $_POST['post_garage'];
			$stmt->execute();
			$stmt = $dbh->prepare("INSERT INTO Property_Size (Property_ID, Land, Floor) 
									VALUES (:property_id, :land, :floor)");
			$stmt->bindParam(':property_id', $result);
			$stmt->bindParam(':land', $land);
			$stmt->bindParam(':floor', $floor);
			$land = $_POST['post_land'];
			$floor = $_POST['post_floor'];
			$stmt->execute();
			echo "Property Posted!";
			//echo "<script> location.href = 'index.php' </script>";
			//echo "Error Code: " . $stmt->errorCode();
		}
		catch(PDOException $e){
			if($e->getCode() === '23000')
			{
				echo "Email already taken.";
			}
			else
			{
			echo "Error: " . $e->getMessage();
			}
		}
	}
}


function property_list() {
	$type="";
	if(isset($_GET['type'])){
		$type = $_GET['type'];
	}
	else {
		$type="";
	}
	$servername = "localhost";
	$username = "root";
	$password = "";
	$i = "'";
	try{
    $dbh = new pdo( "mysql:host=localhost;dbname=lazzypropertiesdb",
                    $username,
                    $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $ex){
		echo 'Connection failed: ' . $ex->getmessage();
	}
	if($type=="forsale"){
		try{
			//Select User With Same Email && Pass
			$stmt = $dbh->prepare("SELECT * FROM Property_List WHERE Type='a'");
			$stmt->execute();
			$count = (int)$stmt->rowCount();
			$results = $stmt->fetchAll();
			$link = 'index.php?source=property-page';
			foreach($results as $row) {
				
				$desc = htmlentities($row['Description']);
				
				
				echo'<div class="col-sm-6 col-md-4 p0">
                    <div class="box-two proerty-item">
                        <div class="item-thumb">
                            <a href="index.php?source=property-page&propId=' . htmlentities($row['Property_ID']) . '" ><img src="assets/img/demo/property-1.jpg"></a>
                            </div>

                                <div class="item-entry overflow">
                                    <h5><a href="property-1.html">' .htmlentities($row['Title']) . '</a></h5>
                                    <div class="dot-hr"></div>
                                    <span class="pull-left"><b> Land :</b>' . htmlentities($row['Land']) . 'sqm </span>
									<br/>
									<span class="pull-left"><b> Floor :</b> ' . htmlentities($row['Floor']) . 'sqm </span>
                                    <br/>
									<span class="pull-left"><b> ' . htmlentities($row['StreetAddress']) .', ' . htmlentities($row['City']) . ', ' . htmlentities($row['State']) . ', ' . htmlentities($row['Country']) . '</b> </span>
                                    <span class="proerty-price pull-right"> &#8369 ' . htmlentities($row['Price']) . '</span>
                                    <p style="display: none;">'. substr($desc,0,50) .'...</p>
                                <div class="property-icon">
                                <img src="assets/img/icon/bed.png">(' . htmlentities($row['Bed']) . ')|
                                <img src="assets/img/icon/shawer.png">(' . htmlentities($row['Bath']) . ')|
                                <img src="assets/img/icon/cars.png">(' . htmlentities($row['Garage']) . ')
												
                            </div>
                        </div> 
                    </div>
                </div>';
				
			}
			// Fetch data from query
			
			//Start PHP Session
			//$_SESSION['ID'] = $result->
			//echo "Log In Successful!";
			//echo "<script> location.href = 'index.php' </script>";
		}
		catch(PDOException $e){
			echo "Error: " . $e->getMessage();
		}
	}
	else if($type=="forrent"){
		try{
			//Select User With Same Email && Pass
			$stmt = $dbh->prepare("SELECT * FROM Property_List WHERE Type='b'");
			$stmt->execute();
			$count = (int)$stmt->rowCount();
			$results = $stmt->fetchAll();
			$link = 'index.php?source=property-page';
			foreach($results as $row) {
				
				$desc = htmlentities($row['Description']);
				
				
				echo'<div class="col-sm-6 col-md-4 p0">
                    <div class="box-two proerty-item">
                        <div class="item-thumb">
                            <a href="index.php?source=property-page&propId=' . htmlentities($row['Property_ID']) . '" ><img src="assets/img/demo/property-1.jpg"></a>
                            </div>

                                <div class="item-entry overflow">
                                    <h5><a href="property-1.html">' .htmlentities($row['Title']) . '</a></h5>
                                    <div class="dot-hr"></div>
                                    <span class="pull-left"><b> Land :</b>' . htmlentities($row['Land']) . 'sqm </span>
									<br/>
									<span class="pull-left"><b> Floor :</b> ' . htmlentities($row['Floor']) . 'sqm </span>
                                    <br/>
									<span class="pull-left"><b> ' . htmlentities($row['StreetAddress']) .', ' . htmlentities($row['City']) . ', ' . htmlentities($row['State']) . ', ' . htmlentities($row['Country']) . '</b> </span>
                                    <span class="proerty-price pull-right"> &#8369 ' . htmlentities($row['Price']) . '</span>
                                    <p style="display: none;">'. substr($desc,0,50) .'...</p>
                                <div class="property-icon">
                                <img src="assets/img/icon/bed.png">(' . htmlentities($row['Bed']) . ')|
                                <img src="assets/img/icon/shawer.png">(' . htmlentities($row['Bath']) . ')|
                                <img src="assets/img/icon/cars.png">(' . htmlentities($row['Garage']) . ')
												
                            </div>
                        </div> 
                    </div>
                </div>';
				
			}
			// Fetch data from query
			
			//Start PHP Session
			//$_SESSION['ID'] = $result->
			//echo "Log In Successful!";
			//echo "<script> location.href = 'index.php' </script>";
		}
		catch(PDOException $e){
			echo "Error: " . $e->getMessage();
		}
	}
	else if($type=="new"){
		try{
			//Select User With Same Email && Pass
			$stmt = $dbh->prepare("SELECT * FROM Property_List WHERE Type='c'");
			$stmt->execute();
			$count = (int)$stmt->rowCount();
			$results = $stmt->fetchAll();
			$link = 'index.php?source=property-page';
			foreach($results as $row) {
				
				$desc = htmlentities($row['Description']);
				
				
				echo'<div class="col-sm-6 col-md-4 p0">
                    <div class="box-two proerty-item">
                        <div class="item-thumb">
                            <a href="index.php?source=property-page&propId=' . htmlentities($row['Property_ID']) . '" ><img src="assets/img/demo/property-1.jpg"></a>
                            </div>

                                <div class="item-entry overflow">
                                    <h5><a href="property-1.html">' .htmlentities($row['Title']) . '</a></h5>
                                    <div class="dot-hr"></div>
                                    <span class="pull-left"><b> Land :</b>' . htmlentities($row['Land']) . 'sqm </span>
									<br/>
									<span class="pull-left"><b> Floor :</b> ' . htmlentities($row['Floor']) . 'sqm </span>
									<br/>
									<span class="pull-left"><b> ' . htmlentities($row['StreetAddress']) .', ' . htmlentities($row['City']) . ', ' . htmlentities($row['State']) . ', ' . htmlentities($row['Country']) . '</b> </span>
                                    <span class="proerty-price pull-right"> &#8369 ' . htmlentities($row['Price']) . '</span>
                                    <p style="display: none;">'. substr($desc,0,50) .'...</p>
                                <div class="property-icon">
                                <img src="assets/img/icon/bed.png">(' . htmlentities($row['Bed']) . ')|
                                <img src="assets/img/icon/shawer.png">(' . htmlentities($row['Bath']) . ')|
                                <img src="assets/img/icon/cars.png">(' . htmlentities($row['Garage']) . ')
												
                            </div>
                        </div> 
                    </div>
                </div>';
				
			}
			// Fetch data from query
			
			//Start PHP Session
			//$_SESSION['ID'] = $result->
			//echo "Log In Successful!";
			//echo "<script> location.href = 'index.php' </script>";
		}
		catch(PDOException $e){
			echo "Error: " . $e->getMessage();
		}
	}
	else if($type=="commercialland"){
		try{
			//Select User With Same Email && Pass
			$stmt = $dbh->prepare("SELECT * FROM Property_List WHERE Type='d'");
			$stmt->execute();
			$count = (int)$stmt->rowCount();
			$results = $stmt->fetchAll();
			$link = 'index.php?source=property-page';
			foreach($results as $row) {
				
				$desc = htmlentities($row['Description']);
				
				
				echo'<div class="col-sm-6 col-md-4 p0">
                    <div class="box-two proerty-item">
                        <div class="item-thumb">
                            <a href="index.php?source=property-page&propId=' . htmlentities($row['Property_ID']) . '" ><img src="assets/img/demo/property-1.jpg"></a>
                            </div>

                                <div class="item-entry overflow">
                                    <h5><a href="property-1.html">' .htmlentities($row['Title']) . '</a></h5>
                                    <div class="dot-hr"></div>
                                    <span class="pull-left"><b> Land :</b>' . htmlentities($row['Land']) . 'sqm </span>
									<br/>
									<span class="pull-left"><b> Floor :</b> ' . htmlentities($row['Floor']) . 'sqm </span>
									<br/>
									<span class="pull-left"><b> ' . htmlentities($row['StreetAddress']) .', ' . htmlentities($row['City']) . ', ' . htmlentities($row['State']) . ', ' . htmlentities($row['Country']) . '</b> </span>
                                    <span class="proerty-price pull-right"> &#8369 ' . htmlentities($row['Price']) . '</span>
                                    <p style="display: none;">'. substr($desc,0,50) .'...</p>
                                <div class="property-icon">
                                <img src="assets/img/icon/bed.png">(' . htmlentities($row['Bed']) . ')|
                                <img src="assets/img/icon/shawer.png">(' . htmlentities($row['Bath']) . ')|
                                <img src="assets/img/icon/cars.png">(' . htmlentities($row['Garage']) . ')
												
                            </div>
                        </div> 
                    </div>
                </div>';
				
			}
			// Fetch data from query
			
			//Start PHP Session
			//$_SESSION['ID'] = $result->
			//echo "Log In Successful!";
			//echo "<script> location.href = 'index.php' </script>";
		}
		catch(PDOException $e){
			echo "Error: " . $e->getMessage();
		}
	}
	else {
		
		try{
			//Select User With Same Email && Pass
			$stmt = $dbh->prepare("SELECT * FROM Property_List");
			$stmt->execute();
			$count = (int)$stmt->rowCount();
			$results = $stmt->fetchAll();
			$link = 'index.php?source=property-page';
			foreach($results as $row) {
				
				$desc = htmlentities($row['Description']);
				
				
				echo'<div class="col-sm-6 col-md-4 p0">
                    <div class="box-two proerty-item">
                        <div class="item-thumb">
                            <a href="index.php?source=property-page&propId=' . htmlentities($row['Property_ID']) . '" ><img src="assets/img/demo/property-1.jpg"></a>
                            </div>

                                <div class="item-entry overflow">
                                    <h5><a href="property-1.html">' .htmlentities($row['Title']) . '</a></h5>
                                    <div class="dot-hr"></div>
                                    <span class="pull-left"><b> Land :</b>' . htmlentities($row['Land']) . 'sqm </span>
									<br/>
									<span class="pull-left"><b> Floor :</b> ' . htmlentities($row['Floor']) . 'sqm </span>
                                    <span class="proerty-price pull-right"> &#8369 ' . htmlentities($row['Price']) . '</span>
									<span class="pull-left"><b> ' . htmlentities($row['StreetAddress']) .', ' . htmlentities($row['City']) . ', ' . htmlentities($row['State']) . ', ' . htmlentities($row['Country']) . '</b> </span>
                                    <p style="display: none;">'. substr($desc,0,50) .' ...</p>
                                <div class="property-icon">
                                <img src="assets/img/icon/bed.png">(' . htmlentities($row['Bed']) . ')|
                                <img src="assets/img/icon/shawer.png">(' . htmlentities($row['Bath']) . ')|
                                <img src="assets/img/icon/cars.png">(' . htmlentities($row['Garage']) . ')
												
                            </div>
                        </div> 
                    </div>
                </div>';
				
			}
			// Fetch data from query
			
			//Start PHP Session
			//$_SESSION['ID'] = $result->
			//echo "Log In Successful!";
			//echo "<script> location.href = 'index.php' </script>";
		}
		catch(PDOException $e){
			echo "Error: " . $e->getMessage();
		}
		
	}
	
}

function property_page(){
	$Id=-1;
	$Status='';
	if(isset($_GET['propId'])){
		$Id = $_GET['propId'];
	}
	else {
		$Id=-1;
	}
	
	$servername = "localhost";
	$username = "root";
	$password = "";
	$i = "'";
	try{
    $dbh = new pdo( "mysql:host=localhost;dbname=lazzypropertiesdb",
                    $username,
                    $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $ex){
		echo 'Connection failed: ' . $ex->getmessage();
	}
	
	if(!$Id==-1){
		try{
			//Select User With Same Email && Pass
			$stmt = $dbh->prepare("SELECT * FROM Property_Page WHERE Property_ID= :idd");
			$stmt->bindParam(':idd', $Id);
			$stmt->execute();
			$count = (int)$stmt->rowCount();
			$results = $stmt->fetchAll();
			$link = 'index.php?source=property-page';
			foreach($results as $row) {
				
				$desc = htmlentities($row['Description']);
				if(htmlentities($row['Type'])=='a'){
					$Status='For Sale';
				}
				else if (htmlentities($row['Type'])=='b'){
					$Status='For Rent';
				}
				else if (htmlentities($row['Type'])=='c'){
					$Status='New';
				}
				else if (htmlentities($row['Type'])=='d'){
					$Status='Commercial and Land';
				}
					
				echo '<div class="clearfix padding-top-40" >

                    <div class="col-md-8 single-property-content prp-style-1 ">
                     <div class="row">
                            <div class="light-slide-item">            
                                <div class="clearfix">
                                    <div class="favorite-and-print">
                                        <a class="add-to-fav" href="#login-modal" data-toggle="modal">
                                            <i class="fa fa-star-o"></i>
                                        </a>
                                        <a class="printer-icon " href="javascript:window.print()">
                                            <i class="fa fa-print"></i> 
                                        </a>
                                    </div> 

                                    <ul id="image-gallery" class="gallery list-unstyled cS-hidden">
                                        <li data-thumb="./assets/img/property-1/property1.jpg"> 
                                            <img src="assets/img/property-1/property1.jpg" />
                                        </li>
                                        <li data-thumb="./assets/img/property-1/property2.jpg"> 
                                            <img src="assets/img/property-1/property3.jpg" />
                                        </li>
                                        <li data-thumb="./assets/img/property-1/property3.jpg"> 
                                            <img src="assets/img/property-1/property3.jpg" />
                                        </li>
                                        <li data-thumb="./assets/img/property-1/property4.jpg"> 
                                            <img src="assets/img/property-1/property4.jpg" />
                                        </li>                                         
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="single-property-wrapper">
                            <div class="single-property-header">                                          
                                <h1 class="property-title pull-left">' . htmlentities($row['Title']) . '</h1>
                                <span class="property-price pull-right">&#8369 ' . htmlentities($row['Price']) . '</span>
                            </div>

                            <div class="property-meta entry-meta clearfix ">   

                                <div class="col-xs-6 col-sm-3 col-md-3 p-b-15">
                                    <span class="property-info-icon icon-tag">                                        
                                        <img src="assets/img/icon/sale-orange.png">
                                    </span>
                                    <span class="property-info-entry">
                                        <span class="property-info-label">Status</span>
                                        <span class="property-info-value">' . $Status . '</span>
                                    </span>
                                </div>

                                <div class="col-xs-6 col-sm-3 col-md-3 p-b-15">
                                    <span class="property-info-icon icon-bath">
                                        <img src="assets/img/icon/os-orange.png">
                                    </span>
                                    <span class="property-info-entry">
                                        <span class="property-info-label">Land</span>
                                        <span class="property-info-value">'.htmlentities($row['Land']).'<b class="property-info-unit">Sqm</b></span>
                                    </span>
                                </div>
								<div class="col-xs-6 col-sm-3 col-md-3 p-b-15">
                                    <span class="property-info icon-area">
                                        <img src="assets/img/icon/room-orange.png">
                                    </span>
                                    <span class="property-info-entry">
                                        <span class="property-info-label">Floor</span>
                                        <span class="property-info-value">'.htmlentities($row['Floor']).'<b class="property-info-unit">Sqm</b></span>
                                    </span>
                                </div>


                                <div class="col-xs-6 col-sm-3 col-md-3 p-b-15">
                                    <span class="property-info-icon icon-bed">
                                        <img src="assets/img/icon/bed-orange.png">
                                    </span>
                                    <span class="property-info-entry">
                                        <span class="property-info-label">Bedrooms</span>
                                        <span class="property-info-value">'.htmlentities($row['Bed']).'</span>
                                    </span>
                                </div>
                                
                                <div class="col-xs-6 col-sm-3 col-md-3 p-b-15">
                                    <span class="property-info-icon icon-garage">
                                        <img src="assets/img/icon/shawer-orange.png">
                                    </span>
                                    <span class="property-info-entry">
                                        <span class="property-info-label">Bathroom</span>
                                        <span class="property-info-value">'.htmlentities($row['Bath']).'</span>
                                    </span>
                                </div>
								
								<div class="col-xs-6 col-sm-3 col-md-3 p-b-15">
                                    <span class="property-info-icon icon-bed">
                                        <img src="assets/img/icon/cars-orange.png">
                                    </span>
                                    <span class="property-info-entry">
                                        <span class="property-info-label">Garage</span>
                                        <span class="property-info-value">'.htmlentities($row['Garage']).'</span>
                                    </span>
                                </div>


                            </div>
                            <!-- .property-meta -->

                            <div class="section">
                                <h4 class="s-property-title">Description</h4>
                                <div class="s-property-content">
                                    <p>'.$desc.'</p>
                                </div>
                            </div>
							
							<div class="section property-video" id="map2"> 
                                <h4 class="s-property-title">Property Map</h4> 
                                <div class="video-thumb" id="map"></div>
								<script>
									function initMap() {
										var myLatLng = {lat: ' . htmlentities($row['Latitude']) . ', lng: ' . htmlentities($row['Longitude']) . '};

										// Create a map object and specify the DOM element for display.
										var map = new google.maps.Map(document.getElementById('.$i.'map'.$i.'), {
										  center: myLatLng,
										  zoom: 17
										});

										// Create a marker and set its position.
										var marker = new google.maps.Marker({
										  map: map,
										  position: myLatLng,
										  title: '.$i.'Hello World!'.$i.'
										});
									}

								</script>
								
								<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA4EFKbbWeDCGWiH4VHV6aQTDVI0op9bP8&callback=initMap"
								async defer></script>
                            </div>
							
							<!-- Start share area -->
                            <div class="section property-share"> 
                                <h4 class="s-property-title">Share width your friends </h4> 
                                <div class="roperty-social">
                                    <ul> 
                                        <li><a title="Share this on dribbble " href="#"><img src="assets/img/social_big/dribbble_grey.png"></a></li>                                         
                                        <li><a title="Share this on facebok " href="#"><img src="assets/img/social_big/facebook_grey.png"></a></li> 
                                        <li><a title="Share this on delicious " href="#"><img src="assets/img/social_big/delicious_grey.png"></a></li> 
                                        <li><a title="Share this on tumblr " href="#"><img src="assets/img/social_big/tumblr_grey.png"></a></li> 
                                        <li><a title="Share this on digg " href="#"><img src="assets/img/social_big/digg_grey.png"></a></li> 
                                        <li><a title="Share this on twitter " href="#"><img src="assets/img/social_big/twitter_grey.png"></a></li> 
                                        <li><a title="Share this on linkedin " href="#"><img src="assets/img/social_big/linkedin_grey.png"></a></li>                                        
                                    </ul>
                                </div>
                            </div>
                            <!-- End share area  -->
                            
                        </div>
                    </div>


                    <div class="col-md-4 p0">
                        <aside class="sidebar sidebar-property blog-asside-right">
                            <div class="dealer-widget">
                                <div class="dealer-content">
                                    <div class="inner-wrapper">

                                        <div class="clear">
                                            <div class="col-xs-4 col-sm-4 dealer-face">
                                                <a href="">
                                                    <img src="assets/img/client-face1.png" class="img-circle">
                                                </a>
                                            </div>
                                            <div class="col-xs-8 col-sm-8 ">
                                                <h3 class="dealer-name">
                                                    <a href="">'.htmlentities($row['First_Name']) . ' ' . htmlentities($row['Last_Name']).'</a><br/>
                                                    <span>Real Estate Agent</span>        
                                                </h3>
                                                <div class="dealer-social-media">
                                                    <a class="twitter" target="_blank" href="">
                                                        <i class="fa fa-twitter"></i>
                                                    </a>
                                                    <a class="facebook" target="_blank" href="">
                                                        <i class="fa fa-facebook"></i>
                                                    </a>
                                                    <a class="gplus" target="_blank" href="">
                                                        <i class="fa fa-google-plus"></i>
                                                    </a>
                                                    <a class="linkedin" target="_blank" href="">
                                                        <i class="fa fa-linkedin"></i>
                                                    </a> 
                                                    <a class="instagram" target="_blank" href="">
                                                        <i class="fa fa-instagram"></i>
                                                    </a>       
                                                </div>

                                            </div>
                                        </div>

                                        <div class="clear">
                                            <ul class="dealer-contacts">                                       
                                                <!--<li><i class="pe-7s-map-marker strong"> </i> 9089 your adress her</li>-->
                                                <li><i class="pe-7s-mail strong"> </i> '.htmlentities($row['Email']).'</li>
                                                <li><i class="pe-7s-call strong"> </i> '.htmlentities($row['Mobile']).'</li>
                                            </ul>
                                            <!--<p>Duis mollis  blandit tempus porttitor curabiturDuis mollis  blandit tempus porttitor curabitur , est non…</p>-->
                                        </div>

                                    </div>
                                </div>
                            </div>';
				
				
				
			}
			// Fetch data from query
			
			//Start PHP Session
			//$_SESSION['ID'] = $result->
			//echo "Log In Successful!";
			//echo "<script> location.href = 'index.php' </script>";
		}
		catch(PDOException $e){
			echo "Error: " . $e->getMessage();
		}
	}
	else {
		echo 'The property is inactive or deleted by the owner.';
	}
	
	
}


?>