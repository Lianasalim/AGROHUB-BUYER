<?php  
    include('connection.php');
// Add this to your PHP script
ini_set("SMTP", "smtp.gmail.com");
ini_set("smtp_port", "587");
ini_set("sendmail_from", "your-email@gmail.com");  // Set your Gmail email address

// Rest of your code...

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $customerName = $_POST["customerName"];
    $contactInfo = $_POST["contactInfo"];
    $orderID = $_POST["orderID"];
    $complaintCategory = $_POST["complaintCategory"];
    $complaintDescription = $_POST["complaintDescription"];
 $attachments = $_POST['attachments'];
try{
$sql = "INSERT INTO process_complaint (customerName,contactInfo,orderID,complaintCategory,complaintDescription,attachments)VALUES('$customerName','$contactInfo','$orderID','$complaintCategory','$complaintDescription','$attachments')";
$s=$con->prepare($sql);
$r=$s->execute();
echo $r;
if($r)
   header("Location: thank_you.html");
else
header("Location:complaint.html");
exit();
}
catch(Exception $e){
}


    // Process the data (you can save it to a database, send emails, etc.)

   // Send confirmation email to the customer
  $to = $contactInfo;
    $subject = "Complaint Submission Confirmation";
    $message = "Thank you for submitting your complaint. We will address it as soon as possible.";
    mail($to, $subject, $message);

    // Redirect to a thank-you page

}
?>
