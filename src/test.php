
<?php
    $to      = 'sp2danny@gmail.com';
    $subject = 'post fran servern';
    $message = 'ingen info just nu' . "\r\n";
    $headers = [];
    $headers['From']     = 'kundtjanst@mind2excellence.se';
    $headers['Reply-To'] = 'kundtjanst@mind2excellence.se';
    $headers['X-Mailer'] = 'PHP/' . phpversion();

    $ok = mail($to, $subject, $message, $headers);

    if ($ok)
        echo "mail() returned true";
    else
        echo "mail() returned false";
?>

