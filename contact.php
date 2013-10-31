<?php 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email =  trim($_POST["email"]);
    $message =  trim($_POST["message"]) ;
    
    // added form validation
    if ($name == "" OR $email == "" OR $message == "") {
        echo "you must specify a value for name and email, and message";
        exit;
    }

    foreach ( $_POST as $value ){
        if( stripos($value, 'Content-Type:') !== FALSE ){
            echo "There was a problem with the information you entered";
            exit;
        }
    }
    //this is to trick spambots
    if ($_POST["address"] != "") {
        echo "Your form submission has an error.";
        exit;
    }
    require_once("inc/phpmailer/class.phpmailer.php");
    $mail = new PHPMailer(); // defaults to using php "mail()"

    if (!$mail->ValidateAddress($email)){
        echo "you must specify a valid email address";
        exit;
    }

    $email_body = "";
    $email_body = $email_body . "Name: " . $name . "<br>";
    $email_body = $email_body . "Email: " . $email . "<br>";
    $email_body = $email_body . "Message: " . $message;

    $mail->SetFrom($email, $name);
    $address = "orders@shirts4mike.com";
    $mail->AddAddress($address, "Shirts 4 Mike");
    $mail->Subject    = "Shirts 4 Mike Contact Form Submission | " . $name;
    $mail->MsgHTML($address, "Shirts 4 Mike");

    if(!$mail->Send()) {
    echo "There was a problem sending the email: " . $mail->ErrorInfo;
    exit;
    } 

    header("Location: contact.php?status=thanks");
    exit;
}
?><?php 
$pageTitle = "Contact Mike";
$section = "contact";
include('inc/header.php'); ?>

	<div class="section page">

		<div class="wrapper">

            <h1>Contact</h1>

            <?php if (isset($_GET["status"]) AND $_GET["status"] == "thanks") { ?>
                <p>Thanks for the email! I&rsquo;ll be in touch shortly!</p>
            <?php } else { ?>

                <p>I&rsquo;d love to hear from you! Complete the form to send me an email.</p>

                <form method="post" action="contact.php">

                    <table>
                        <tr>
                            <th>
                                <label for="name">Name</label>
                            </th>
                            <td>
                                <input type="text" name="name" id="name">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="email">Email</label>
                            </th>
                            <td>
                                <input type="text" name="email" id="email">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="message">Message</label>
                            </th>
                            <td>
                                <textarea name="message" id="message"></textarea>
                            </td>
                        </tr>
                        <!-- tricking spam robots by using hidden css forms --> 
                        <tr style="display: none;">
                            <th>
                                <label for="address">Address</label>
                            </th>
                            <td>
                                <input type="text" name="address" id="address">
                                <p>Leave this field blank if you are human</p>
                            </td>
                        </tr>                   
                    </table>
                    <input type="submit" value="Send">

                </form>

            <?php } ?>

        </div>

	</div>

<?php include('inc/footer.php') ?>
