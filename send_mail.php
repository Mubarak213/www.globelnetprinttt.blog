<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = "your_email@example.com"; // Replace with your email
    $from = $_POST['email'];
    $name = $_POST['name'];
    $subject = "New Attachment from $name";

    $message = "You have received a new file from $name <$from>";

    $file = $_FILES['attachment']['tmp_name'];
    $filename = $_FILES['attachment']['name'];

    if (is_uploaded_file($file)) {
        $content = chunk_split(base64_encode(file_get_contents($file)));
        $uid = md5(uniqid(time()));
        $filename = basename($filename);

        $header = "From: $name <$from>\r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-Type: multipart/mixed; boundary=\"$uid\"\r\n\r\n";

        $body = "--$uid\r\n";
        $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
        $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $body .= "$message\r\n\r\n";
        $body .= "--$uid\r\n";
        $body .= "Content-Type: application/octet-stream; name=\"$filename\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "Content-Disposition: attachment; filename=\"$filename\"\r\n\r\n";
        $body .= "$content\r\n\r\n";
        $body .= "--$uid--";

        if (mail($to, $subject, $body, $header)) {
            echo "Mail sent successfully.";
        } else {
            echo "Mail sending failed.";
        }
    } else {
        echo "File upload failed.";
    }
}
?>
