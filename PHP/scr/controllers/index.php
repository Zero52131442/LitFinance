<?php
require_once("./BaseGen.php");
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    show_entries();
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $body_parmas = json_decode(file_get_contents('php://input'), true);
    create_entries($body_parmas["fname"], $body_parmas["lastname"], $body_parmas["age"]);
} elseif ($_SERVER["REQUEST_METHOD"] == "PUT") {
    $body_parmas = json_decode(file_get_contents('php://input'), true);
    record_change(
        $body_parmas["new_name"],
        $body_parmas["new_lastname"],
        $body_parmas["new_age"],
        $body_parmas["old_name"],
        $body_parmas["old_lastname"],
        $body_parmas["old_age"]
    );
} elseif ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    $body_parmas = json_decode(file_get_contents('php://input'), true);
    dele_entries($body_parmas["fname"], $body_parmas["lastname"]);
}
