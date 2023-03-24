<?php


//Funkcija koja upravlja oznakom za početak
function handle_open_element($p, $element, $attributes) {

    //Ovisno o oznaci $element stvaraj sljedeće div,p h2 u ovisnosti o atributima( id, ime,prezime, email, spol, zivotopis, slika)
    switch ($element) {

        case 'ID':
            echo '<h2>'; // id su boldani  odnoosnoo  h2
            break;

        case 'IME':
            echo "<p>Ime: ";
            break;

        case 'PREZIME':
            echo "<p>Prezime: ";
            break;

        case 'EMAIL':
            echo "<p>E-MAIL: ";
            break;

        case 'SPOL':
            echo "<p>SPOL: ";
            break;

        case 'ZIVOTOPIS':
            echo "<p>ZIVOTOPIS: ";
            break;

        case 'SLIKA':
            echo "<img src=";
            break;


    }

}

//Funkcija za rukovanje oznakom za kraj
function handle_close_element($p, $element) {

    //Ovisno o oznaci radi sljedeće, atributi su isti
    switch ($element) {

        case 'ID':
            echo '</h2>';
            break;

        case 'IME':
            echo '</p>';
            break;

        case 'PREZIME':
            echo '</p>';
            break;

        case 'EMAIL':
            echo '</p>';
            break;

        case 'SPOL':
            echo '</p>';
            break;

        case 'ZIVOTOPIS':
            echo '</p>';
            break;

        case 'SLIKA': //Pokaži sliku
            echo "border=\"0\"><br>";
            break;



    }

}

//Ispisujemoo sadržaj
function handle_character_data($p, $cdata) {
    echo $cdata;
}


//Stvaramo xml parser
$p = xml_parser_create();

//Postavljamo funkcije za rukovanje

xml_set_element_handler($p, 'handle_open_element', 'handle_close_element');
xml_set_character_data_handler($p, 'handle_character_data');

//učitavamo datooteku LV2.xml koju smo skinuli
$file = 'LV2.xml';
$fp = @fopen($file, 'r') or die("<p>Ne možemo otvoriti datoteku '$file'.</p></body></html>");
while ($data = fread($fp, 4096)) {
    xml_parse($p, $data, feof($fp));
}

//Zatvaramo parseer
xml_parser_free($p);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LV2-ZAD3</title>
    <link rel="stylesheet" href="zad3.css">
</head>
<body>

</body>
</html>
