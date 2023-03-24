<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>zadatak2</title>
</head>

<body>
<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="file">
    <button type="submit">Upload doc i kriptiraj doc</button>
</form>
<?php

// Provjeravamo  da li je forma uopće poslana
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Provjeravamo da li postoji tadatoteka
    if (!empty($_FILES['file']['tmp_name'])) {

        // standardne postavke za kriptiranje
        $method = 'aes-256-cbc';
        $key = 'ključ_za_kriptiranje';
        $iv_length = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($iv_length);

        // Ime kriptiranog dokumenta kooje spremamo u folder uploads koji se nalazi u istoj datoteci
        $encrypted_file = 'uploads/' . $_FILES['file']['name'] . '.enc';

        // Otvaramo datoteku za čitanje i kriptiranje
        $input_file = fopen($_FILES['file']['tmp_name'], 'rb');
        $output_file = fopen($encrypted_file, 'wb');

        // Kriptiramo samu datoteeku
        fwrite($output_file, $iv);
        while (!feof($input_file)) {
            $plaintext = fread($input_file, 8192);
            $ciphertext = openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv);
            fwrite($output_file, $ciphertext);
        }

        // Zatvaramo datoteke
        fclose($input_file);
        fclose($output_file);

        // ispisujemo poruku za korisnika u ovisnosti je li datoteka uspješno uploadana i kriptirana ili je neuspješno
        echo 'Datoteka je uspješno uploadana i kriptirana.';
    } else {
        echo 'Nije odabrana datoteka za upload.';
    }
}
?>
<?php

$encrypted_files_dir = 'uploads/';

// Postavke za dekriptiranje
$method = 'aes-256-cbc';
$key = 'ključ_za_kriptiranje';

// Dohvaćamo sve kriptirane datoteke u mapi
$encrypted_files = glob($encrypted_files_dir . '*.enc');

// Prolazimo kroz svaku kriptiranu datoteku
foreach ($encrypted_files as $encrypted_file) {

    // Ime dekriptirane datoteke
    $decrypted_file = str_replace('.enc', '', $encrypted_file);

    // Otvoaramo datoteku za čitanje i dekriptiranje
    $input_file = fopen($encrypted_file, 'rb');
    $output_file = fopen($decrypted_file, 'wb');

    
    $iv_length = openssl_cipher_iv_length($method);
    $iv = fread($input_file, $iv_length);

    // Dekriptiramo datoteku
    while (!feof($input_file)) {
        $ciphertext = fread($input_file, 8192);
        $plaintext = openssl_decrypt($ciphertext, $method, $key, OPENSSL_RAW_DATA, $iv);
        fwrite($output_file, $plaintext);
    }

    // Zatvaramo datoteke
    fclose($input_file);
    fclose($output_file);

    // Prikaz linka za preuzimanje nakon što je datoteka kriptirana 
    echo '<a href="' . $decrypted_file . '">Preuzmite ' . basename($decrypted_file) . '</a><br>';
}
?>

</body>

</html>
