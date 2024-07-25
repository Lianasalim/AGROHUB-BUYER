<?php      
    include('connection.php');  
	$name = $_POST['name1'];  
    $building = $_POST['building-no1'];  
    $address = $_POST['address1'];  
    $locality = $_POST['locality1'];
    $pin = $_POST['pin-code1'];
    $phone = $_POST['phone-number1'];        
        
try{
$sql = "INSERT INTO address (name,building_no,address,locality,pincode,phone)VALUES('$name','$building','$address','$locality','$pin','$phone')WHERE email = ? ";
}
catch(Exception $e){
}
        $result = mysqli_query($con, $sql);  
?>  

