<?php



function base_generation()
{

    $link = mysqli_connect('mysql', 'root', 'root');
    $res = mysqli_query($link, "SHOW DATABASES");


    $check = 0;
    while ($row = mysqli_fetch_assoc($res)) {
        if ($row['Database'] == "Users") {
            $check = 1;
        }
    }
    if ($check == 0) {
        mysqli_query($link, "CREATE DATABASE `Users`");
    }
}


function create_table()
{

    $link = mysqli_connect('mysql', 'root', 'root', 'Users');
    $res = mysqli_query($link, "SHOW TABLES");


    $check = 0;
    while ($row = mysqli_fetch_assoc($res)) {

        if ($row['Tables_in_Users'] == "personal_data") {
            $check = 1;
        }
    }
    if ($check == 0) {
        mysqli_query(mysqli_connect('mysql', 'root', 'root', 'Users'), "CREATE TABLE personal_data  (
            name varchar(30),
            lastname varchar(30),
            age int
            );");
    }
}
function show_entries()
{
    $res = mysqli_query(mysqli_connect('mysql', 'root', 'root', 'Users'), "SELECT * FROM personal_data");
    $index = 1;
    $answer = "";
    while ($row = mysqli_fetch_assoc($res)) {

        $answer = $answer . sprintf("запись № %d", $index) . " : name = " . $row['name'] . " lastname = " . $row['lastname'] . " age = " . $row['age'] . "\n";
        $index++;
    }
    if ($answer == "") {
        echo 'Записей нет в таблице нет';
    } else {
        echo $answer;
    }
}

function create_entries($name, $lastname, $age)
{
    mysqli_query(mysqli_connect('mysql', 'root', 'root', 'Users'), "INSERT INTO personal_data (name, lastname, age)
    VALUES ('" . $name . "', '" . $lastname . "', " . $age . ");");
}

function record_change($name, $lastname, $age, $old_nam1, $old_nam2, $old_age)
{

    mysqli_query(mysqli_connect('mysql', 'root', 'root', 'Users'), "UPDATE personal_data
    SET name = '" . $name . "',
    lastname = '" . $lastname . "',
    age = '" . $age . "'
    WHERE name = '" . $old_nam1 . "'AND
    lastname = '" . $old_nam2 . "'AND
    age = '" . $old_age . "';");
}
function dele_entries($name, $lastname)
{
    mysqli_query(mysqli_connect('mysql', 'root', 'root', 'Users'), "DELETE FROM personal_data
WHERE name='" . $name . "' AND
lastname='" . $lastname . "'
;");
}
