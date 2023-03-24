<?php
// konfiguracija baze podataka phpMyAdmin
$servername = "localhost";
$username = "miran";
$password = "miran";
$dbname = "diplradovi";

// spajamo se na bazu podataka sa već postavljenim parametrima
$conn = new mysqli($servername, $username, $password, $dbname);

// provjera uspješnosti spajanja
if ($conn->connect_error) {
    die("Greška prilikom spajanja na bazu podataka: " . $conn->connect_error);
}

// dohvaćanje popisa svih tablica iz baze podataka
$tables = array();
$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch_row()) {
    $tables[] = $row[0];
}

// prolazak kroz sve dane tablice i dohvaćanje podataka
$content = "";
foreach ($tables as $table) {
    $result = $conn->query("SELECT * FROM $table");
    $num_fields = $result->field_count;

    // kreiramo SQL upit za svaki redak
    $content .= "INSERT INTO $table (";
    for ($i = 0; $i < $num_fields; $i++) {
        $field = $result->fetch_field();
        $content .= "`" . $field->name . "`";
        if ($i < $num_fields - 1) {
            $content .= ", ";
        }
    }
    $content .= ") VALUES\n";

    // dodajemo vrijednosti u SQL upit
    while ($row = $result->fetch_row()) {
        $content .= "(";
        for ($i = 0; $i < $num_fields; $i++) {
            $content .= "'" . $conn->real_escape_string($row[$i]) . "'";
            if ($i < $num_fields - 1) {
                $content .= ", ";
            }
        }
        $content .= ")";
        if ($row != end($result->fetch_all())) {
            $content .= ",\n";
        } else {
            $content .= ";\n";
        }
    }
}
$conn->close();

// spremamo  backup u formi nazivu backup_podatci-o-vremenu-backupa.txt
$backup_file = "backup_" . date("Y-m-d_H-i-s") . ".txt";
$handle = fopen($backup_file, "w");
fwrite($handle, $content);
fclose($handle);

// zipaamoo backup pomoću funkcije i provjeravamo uopće može li se otvoriti
$zip_file = "backup_" . date("Y-m-d_H-i-s") . ".zip";
$zip = new ZipArchive();
if ($zip->open($zip_file, ZIPARCHIVE::CREATE) !== TRUE) {
    die("Greška prilikom stvaranja zip datoteke");
}
$zip->addFile($backup_file);
$zip->close();

// brisemo backup u txt dat
unlink($backup_file);

echo "Backup baze podataka spremljen je u " . $zip_file;
?>
