<?php
$variables = [
    "title" => "club elec electrogram",
    "lang" => "fr",
    "ext_url" => "http://localhost/public",

    "mysql_address" => "127.0.0.1",
    "mysql_port" => "3306",
    "mysql_database" => "scrapbook",
    "mysql_username" => "scrapbook",
    "mysql_password" => "scrapbook",

    "mode" => "prod"
];
foreach ($variables as $key => $value) {
    putenv("$key=$value");
}
