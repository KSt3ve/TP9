<?php
include 'connexpdo.php';

$dsn = 'pgsql:host=localhost;port=5432;dbname=notes;';
$user = 'postgres';
$password = 'Slender44';
$idcon = connexpdo($dsn, $user, $password);

header ("Content-type: image/png");
$image = imagecreate(800,200);

$grey = imagecolorallocate($image, 96, 96, 96);
$noir = imagecolorallocate($image, 0, 0, 0);
$bleu = imagecolorallocate($image, 0, 0, 255);
$bleuclair = imagecolorallocate($image, 156, 227, 254);


imagestring($image, 4, 300, 15, "Notes des etudiants E1 et E2 !", $noir);

$r1 = $idcon->prepare('SELECT * FROM notes where etudiant = ?');
$r1->execute(array("E1"));
$moyenne1 = 0;
$div = 0;
$esp = 0;
$last=0;
while ($data = $r1->fetch()) {
    if($div==0){
        $last = $data['note'];
    }
    $div++;
    imageline($image, $esp, 100-$last, $esp+100, 100-$data['note'], $bleu);
    $esp =  $esp + 100;
    $last = $data['note'];
    $moyenne1 = $moyenne1 + $data['note']."<br>";
}

$r2 = $idcon->prepare('SELECT * FROM notes where etudiant = ?');
$r2->execute(array("E2"));
$moyenne2 = 0;
$div = 0;
$esp = 0;
$last=0;
while ($data = $r2->fetch()) {
    if($div==0){
        $last = $data['note'];
    }
    $div++;
    imageline($image, $esp, 100-$last, $esp+100, 100-$data['note'], $bleuclair);
    $esp =  $esp + 100;
    $last = $data['note'];
    $moyenne2 = $moyenne2 + $data['note']."<br>";
}


$queryAVG = "SELECT round(avg(note), 4) FROM notes WHERE etudiant = ?";

$result1 = $idcon->prepare($queryAVG);
$result1->execute(array("E1"));
$r1 = $result1->fetch();

$result2 = $idcon->prepare($queryAVG);
$result2->execute(array("E2"));
$r2 = $result2->fetch();

imagestring($image, 4, 500, 160, "Moyenne des notes  de E1 :".$r1[0], $noir);
imagestring($image, 4, 500, 180, "Moyenne des notes  de E2 :".$r2[0], $noir);

imagestring($image, 4, 10, 160, "E1", $bleu);
imagestring($image, 4, 10, 180, "E2", $bleuclair);

imagepng($image);

?>

