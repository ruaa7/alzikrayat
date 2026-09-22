<?php
/**
 * Class Database
 *
 * Singleton PDO connection wrapper for the Data Tier.
 * Ensures a single, reused PDO connection across the whole request
 * lifecycle instead of opening a new connection per Model
 *
 * @package config
 */
class Database
{
    /** @var Database|null Holds the single instance of this class */
    private static ?Database $instance = null;

    /** @var PDO The active PDO connection handle */
    private PDO $connection;

    //Connection settings 
    private string $host    = '127.0.0.1';
    private string $dbName  = 'alzikrayat';
    private string $user    = 'root';
    private string $pass    = '';
    private string $charset = 'utf8mb4';

    /**
     * Private constructor (Singleton pattern).
     * Opens the PDO connection with safe default attributes:
     * exceptions on error, real prepared statements (no emulation)
     * to protect against SQL injection.
     */
    private function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset={$this->charset}";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->connection = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            //Never leak credentials or raw DB errors to the client
            http_response_code(500);
            die('Database connection failed. Please check config/database.php settings.');
        }
    }

    /**
     * Returns the single shared Database instance, creating it on
     * first call
     *
     * @return Database
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Returns the underlying PDO connection for running queries
     *
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    //Prevent cloning and unserialization of the singleton
    private function __clone() {}
    public function __wakeup()
    {
        throw new Exception('Cannot unserialize a singleton.');
    }
}
