<?php
/**
 * PDO Database Class
 * Connect to database
 * Create prepared statements
 * Bind values
 * Return rows and results
 */
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct() {
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false
        );

        // Create PDO instance
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
            debug_log("Database connection established successfully");
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            // Log the error instead of displaying it directly
            error_log('Database Connection Error: ' . $this->error);
            
            // In development environment, show detailed error
            if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
                echo '<div style="background: #f8d7da; color: #721c24; padding: 10px; margin: 10px; border-radius: 5px; border: 1px solid #f5c6cb;">
                    <h3>Database Connection Error</h3>
                    <p>' . htmlspecialchars($this->error) . '</p>
                    <p>Check your database configuration in app/config/config.php</p>
                    <ul>
                      <li>Host: ' . htmlspecialchars($this->host) . '</li>
                      <li>Database: ' . htmlspecialchars($this->dbname) . '</li>
                      <li>User: ' . htmlspecialchars($this->user) . '</li>
                    </ul>
                </div>';
            } else {
                // In production, show user-friendly message
                echo '<div style="text-align: center; padding: 50px;">
                    <h2>Temporarily Unavailable</h2>
                    <p>The application is currently unavailable. Please try again later.</p>
                </div>';
            }
            
            exit; // Stop execution to prevent further errors
        }
    }

    // Prepare statement with query
    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Bind values
    public function bind($param, $value, $type = null) {
        if(is_null($type)) {
            switch(true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($param, $value, $type);
    }

    // Execute the prepared statement
    public function execute() {
        return $this->stmt->execute();
    }

    // Get result set as array of objects
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    // Get single record as object
    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }

    // Get row count
    public function rowCount() {
        return $this->stmt->rowCount();
    }

    // Start transaction
    public function beginTransaction() {
        return $this->dbh->beginTransaction();
    }

    // Commit transaction
    public function commit() {
        return $this->dbh->commit();
    }

    // Rollback transaction
    public function rollback() {
        return $this->dbh->rollBack();
    }

    // Get last inserted ID
    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }
}