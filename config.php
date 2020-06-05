<?php
//STEAMAuth Config
$steamauth = [
    "apikey" => "", // Your Steam WebAPI-Key found at https://steamcommunity.com/dev/apikey
    "domainname" => "", // The main URL of your website displayed in the login page
    "logoutpage" => "", // Page to redirect to after a successfull logout (from the directory the SteamAuth-folder is located in) - NO slash at the beginning!
    "loginpage" => "" // Page to redirect to after a successfull login (from the directory the SteamAuth-folder is located in) - NO slash at the beginning!
    // Bon nous on a pas de loginpage ou logoutpage donc tu met domainname = loginpage = logoutpage
];

//ADMINS
$admins = [
    "76561198102840842",
    "76561198111287499",
    "76561198018127285",
    "76561198145630520",
    "76561198161439324"
];

//MYSQL
$mysql = [
    "dbname" => "awarn2",
    "hote" => "127.0.0.1",
    "mdp" => "password",
    "username" => "root",
    "port" => 3306,
];