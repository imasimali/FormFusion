<?php
    require __DIR__ . '/../include/phpdotenv/Loader.php';
    require __DIR__ . '/../include/phpdotenv/Dotenv.php';
    $dotenv = new Dotenv\Dotenv(__DIR__ . '/../../');
    $dotenv->load();