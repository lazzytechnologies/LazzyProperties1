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

?>