
<?php

$email = "sp2danny@gmail.com";

if ($email != "")
{
    $em_to   = $email;
    $em_subj = 'Orderbekräftelse från mind2excellence.se';
    $em_msg  = 'Välkommen som kund' . "\r\n";
    $em_msg .= 'Här är din länk' . "\r\n";
    $em_msg .= 'https://www.mind2excellence.se/site/common/login.php' . "\r\n";
    $em_hdr  = [];
    $em_hdr['From']     = 'kundtjanst@mind2excellence.se';
    $em_hdr['Reply-To'] = 'kundtjanst@mind2excellence.se';
    $em_hdr['X-Mailer'] = 'PHP/' . phpversion();

    $ok = mail($em_to, $em_subj, $em_msg, $em_hdr);
}


?>

