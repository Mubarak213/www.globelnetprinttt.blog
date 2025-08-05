<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to = 'your@email.com';
    $subject = $_POST['subject'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // File handling
    $file_tmp = $_FILES['attachment']['tmp_name'];
    $file_name = $_FILES['attachment']['name'];
    $file_size = $_FILES['attachment']['size'];
    $file_type = $_FILES['attachment']['type'];

    if (is_uploaded_file($file_tmp)) {
        $file_content = chunk_split(base64_encode(file_get_contents($file_tmp)));

        $boundary = md5(time());

        // Headers
        $headers = "From: $name <$email>\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"".$boundary."\"\r\n";

        // Email body
        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/plain; charset=utf-8\r\n\r\n";
        $body .= "$message\r\n\r\n";

        $body .= "--$boundary\r\n";
        $body .= "Content-Type: $file_type; name=\"$file_name\"\r\n";
        $body .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= "$file_content\r\n";
        $body .= "--$boundary--";

        // Send email
        if (mail($to, $subject, $body, $headers)) {
            echo "Email sent successfully with attachment.";
        } else {
            echo "Failed to send email.";
        }
    } else {
        echo "File upload failed.";
    }
} else {
    echo "Invalid request.";
}
?>
