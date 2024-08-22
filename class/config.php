<?php
session_start();

// Configurações do banco de dados
define('SERVER','localhost');
define('USER','root');
define('PASSWORD','');
define('DB','login');

function clearPost($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}