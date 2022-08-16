<?php

echo "<pre>";
$link = mysqli_connect('mysql','root','root');
$res = mysqli_query($link,"SHOW DATABASES");

while ($row = mysqli_fetch_assoc($res)) {
    echo $row['Database'] . "\n";
}


?>