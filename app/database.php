<?php
require_once 'core/config.php';

function db_connect() {
    try {
        $ca_paths = [
            '/etc/ssl/certs/ca-certificates.crt',
            '/etc/ssl/cert.pem',
            '/etc/pki/tls/certs/ca-bundle.crt',
        ];
        
        $ca_path = null;
        foreach ($ca_paths as $path) {
            if (file_exists($path)) {
                $ca_path = $path;
                break;
            }
        }
        
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_SSL_CA => $ca_path,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true,
        );
        
        $dbh = new PDO(
            'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_DATABASE,
            DB_USER,
            DB_PASS,
            $options
        );
        return $dbh;
    } catch (PDOException $e) {
        error_log('Database connection error: ' . $e->getMessage());
        return null;
    }
}