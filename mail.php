<?php
error_reporting(E_ALL);
require_once('class.phpmailer.php');
include("class.smtp.php");

if (isset($_POST['MasterInner_submit'])) {
    // Capture form data
    $name     = $_POST["MasterInner_txtName"] ?? '';
    $email    = $_POST["MasterInner_txtEmail"] ?? '';
    $phone    = $_POST["MasterInner_txtMobile"] ?? '';
    $service  = $_POST["preferred_time"] ?? '';
    $callTime = $_POST["preferred_time"] ?? '';
    $source   = $_SERVER['HTTP_REFERER'] ?? '';

   
    $country = $_POST["country"] ?? '';
    $Website = "nexgen Landing Page ";
    $utmSource   = $_GET['utm_source'] ?? '';
    $utmMedium   = $_GET['utm_medium'] ?? '';
    $utmCampaign = $_GET['utm_campaign'] ?? '';
    
    // Compose Email
    $subject = "$name has submitted a request on cwm-course-in-india";
    $body = "
        <html><head><title>Form Submission</title></head><body>
        <table border='1' cellpadding='5' cellspacing='0'>
            <tr bgcolor='#b0e1c6'><td colspan='2'><strong>Form Details</strong></td></tr>
            <tr><td>Full Name</td><td>{$name}</td></tr>
            <tr><td>Email ID</td><td>{$email}</td></tr>
            <tr><td>Phone No.</td><td>{$phone}</td></tr>
            <tr><td>Selected Service</td><td>{$service}</td></tr>
            <tr><td>Preferred Time for Call</td><td>{$callTime}</td></tr>
            <tr><td>Source URL</td><td>{$source}</td></tr>
            <tr><td>UTM Source</td><td>{$utmSource}</td></tr>
            <tr><td>UTM Medium</td><td>{$utmMedium}</td></tr>
            <tr><td>UTM Campaign</td><td>{$utmCampaign}</td></tr>
        </table>
        </body></html>
    ";

    // Send Email
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = "feedback@aafmindia.co.in";
    $mail->Password = "admin_123456";
    $mail->setFrom('feedback@aafmindia.co.in', 'AAFM India');
    $mail->addAddress('info@aafmindia.co.in', 'Deepak');
	
    $mail->Subject = $subject;
    $mail->Body    = $body;

    if ($mail->send()) {
        // Call New CRM API
        $apiUrl = 'https://sp.crmapp.in.net/Enquiry/Add';
        $apiKey = '20ab842385b54c94bdb257a5a84b6bff';

        $postData = [
            "PersonName"         => $name,
            "CompanyName"        => "",
            "MobileNo"           => $phone,
            "MobileNo1"          => "",
            "MobileNo2"          => "",
            "EmailID"            => $email,
            "EmailID1"           => "",
            "EmailID2"           => "",
            "City"               => "",
            "State"              => "",
            "Country"            => $country,
            "CountryCode"        => "+91",
            "CountryCode1"       => "",
            "CountryCode2"       => "",
            "PinCode"            => "",
            "ResidentialAddress" => "",
            "OfficeAddress"      => "",
            "SourceName"         => $Website,
            "MediumName"         => $utmMedium,
            "CampaignName"       => $utmCampaign,
            "InitialRemarks"     => $source
        ];

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'kit19-Auth-Key: ' . $apiKey,
            'Content-Length: ' . strlen(json_encode($postData))
        ]);

        $response = curl_exec($ch);
        if ($response === false) {
            error_log('cURL error: ' . curl_error($ch));
        } else {
            $responseData = json_decode($response, true);
            // Optional: log or handle $responseData
        }

        curl_close($ch);

        // Redirect
        header("Location: Thank-You.html");
        exit;
    } else {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
} else {
    echo "Invalid request.";
}

elseif(isset($_GET['submitpop']))


{


}


?>
