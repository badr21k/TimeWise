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
            PDO::ATTR_TIMEOUT => 5, // 5 second connection timeout
        );
        
        // Only add SSL options if ca_path exists
        if ($ca_path) {
            $options[PDO::MYSQL_ATTR_SSL_CA] = $ca_path;
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
        }
        
        $dbh = new PDO(
            'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_DATABASE,
            DB_USER,
            DB_PASS,
            $options
        );
        
        // Test the connection
        $dbh->query('SELECT 1');
        
        return $dbh;
    } catch (PDOException $e) {
        error_log('Database connection error: ' . $e->getMessage());
        error_log('Connection details: ' . DB_HOST . ':' . DB_PORT . ' / ' . DB_DATABASE);
        return null;
    }
}