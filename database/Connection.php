<?php

// Read from environment variables, fallback to localhost for development
$host = getenv('DATABASE_HOST') ?: 'localhost';
$user = getenv('DATABASE_USER') ?: 'root';
$password = getenv('DATABASE_PASSWORD') ?: '';
$database = getenv('DATABASE_NAME') ?: 'norsu_system';

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!class_exists('Connection')) {
    class Connection
    {
        private static ?mysqli $instance = null;

        public static function getInstance(): mysqli
        {
            if (self::$instance instanceof mysqli) {
                return self::$instance;
            }

            $host = getenv('DATABASE_HOST') ?: 'localhost';
            $user = getenv('DATABASE_USER') ?: 'root';
            $password = getenv('DATABASE_PASSWORD') ?: '';
            $database = getenv('DATABASE_NAME') ?: 'norsu_system';

            self::$instance = mysqli_connect($host, $user, $password, $database);

            if (!self::$instance) {
                die("Connection failed: " . mysqli_connect_error());
            }

            return self::$instance;
        }
    }
}

?>
