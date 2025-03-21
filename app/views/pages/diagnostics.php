<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="page-container">
    <div class="section">
        <div class="section-header">
            <h1>System Diagnostics</h1>
            <p>This page helps diagnose potential issues with the application.</p>
        </div>

        <div class="diagnostics-container">
            <!-- PHP Info -->
            <div class="diagnostics-card">
                <h2>PHP Environment</h2>
                <table class="diagnostics-table">
                    <tr>
                        <td>PHP Version</td>
                        <td><?php echo phpversion(); ?></td>
                    </tr>
                    <tr>
                        <td>Server Software</td>
                        <td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
                    </tr>
                    <tr>
                        <td>Document Root</td>
                        <td><?php echo $_SERVER['DOCUMENT_ROOT']; ?></td>
                    </tr>
                    <tr>
                        <td>Memory Limit</td>
                        <td><?php echo ini_get('memory_limit'); ?></td>
                    </tr>
                    <tr>
                        <td>Upload Max File Size</td>
                        <td><?php echo ini_get('upload_max_filesize'); ?></td>
                    </tr>
                    <tr>
                        <td>Max Execution Time</td>
                        <td><?php echo ini_get('max_execution_time'); ?> seconds</td>
                    </tr>
                </table>
            </div>

            <!-- Database Connection -->
            <div class="diagnostics-card">
                <h2>Database Connection</h2>
                <?php
                try {
                    $testDb = new Database();
                    // Test a simple query
                    $testDb->query("SELECT 1");
                    $testDb->execute();
                    echo '<div class="success-message"><i class="fas fa-check-circle"></i> Database connection successful</div>';
                    
                    // Show database info
                    echo '<table class="diagnostics-table">';
                    echo '<tr><td>Host</td><td>' . DB_HOST . '</td></tr>';
                    echo '<tr><td>Database</td><td>' . DB_NAME . '</td></tr>';
                    echo '<tr><td>User</td><td>' . DB_USER . '</td></tr>';
                    echo '</table>';
                } catch (Exception $e) {
                    echo '<div class="error-message"><i class="fas fa-exclamation-circle"></i> Database connection failed</div>';
                    echo '<p>Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
                }
                ?>
            </div>

            <!-- File System -->
            <div class="diagnostics-card">
                <h2>File System Check</h2>
                <table class="diagnostics-table">
                    <?php
                    $paths = [
                        'App Root' => APPROOT,
                        'Config Directory' => APPROOT . '/config',
                        'Controllers Directory' => APPROOT . '/controllers',
                        'Models Directory' => APPROOT . '/models',
                        'Views Directory' => APPROOT . '/views',
                        'Public Directory' => dirname(APPROOT) . '/public',
                        'Audio Samples Directory' => dirname(APPROOT) . '/public/audio/demo'
                    ];

                    foreach ($paths as $name => $path) {
                        echo '<tr>';
                        echo '<td>' . $name . '</td>';
                        if (file_exists($path)) {
                            echo '<td class="success-text">Exists</td>';
                            echo '<td>' . (is_writable($path) ? '<span class="success-text">Writable</span>' : '<span class="error-text">Not Writable</span>') . '</td>';
                        } else {
                            echo '<td colspan="2" class="error-text">Not Found</td>';
                        }
                        echo '</tr>';
                    }
                    ?>
                </table>
            </div>

            <!-- Audio Files Check -->
            <div class="diagnostics-card">
                <h2>Audio Files Check</h2>
                <table class="diagnostics-table">
                    <tr>
                        <th>Sample File</th>
                        <th>Status</th>
                        <th>Path</th>
                    </tr>
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        $samplePath = dirname(APPROOT) . '/public/audio/demo/sample_' . $i . '.mp3';
                        echo '<tr>';
                        echo '<td>Sample ' . $i . '</td>';
                        
                        if (file_exists($samplePath)) {
                            echo '<td class="success-text">Found</td>';
                            echo '<td>' . htmlspecialchars($samplePath) . '</td>';
                        } else {
                            echo '<td class="error-text">Missing</td>';
                            echo '<td>' . htmlspecialchars($samplePath) . '</td>';
                        }
                        
                        echo '</tr>';
                    }
                    ?>
                </table>
            </div>

            <!-- URL Test -->
            <div class="diagnostics-card">
                <h2>URL Configuration</h2>
                <table class="diagnostics-table">
                    <tr>
                        <td>URL_ROOT</td>
                        <td><?php echo URL_ROOT; ?></td>
                    </tr>
                    <tr>
                        <td>SITE_NAME</td>
                        <td><?php echo SITE_NAME; ?></td>
                    </tr>
                    <tr>
                        <td>Current URL</td>
                        <td><?php echo getCurrentUrl(); ?></td>
                    </tr>
                    <tr>
                        <td>Request URI</td>
                        <td><?php echo $_SERVER['REQUEST_URI']; ?></td>
                    </tr>
                </table>
            </div>

            <!-- Session Check -->
            <div class="diagnostics-card">
                <h2>Session Configuration</h2>
                <table class="diagnostics-table">
                    <tr>
                        <td>Session Active</td>
                        <td><?php echo session_status() === PHP_SESSION_ACTIVE ? '<span class="success-text">Yes</span>' : '<span class="error-text">No</span>'; ?></td>
                    </tr>
                    <tr>
                        <td>Session Name</td>
                        <td><?php echo session_name(); ?></td>
                    </tr>
                    <tr>
                        <td>Session ID</td>
                        <td><?php echo session_id(); ?></td>
                    </tr>
                    <tr>
                        <td>User Logged In</td>
                        <td><?php echo isLoggedIn() ? '<span class="success-text">Yes</span>' : '<span class="warning-text">No</span>'; ?></td>
                    </tr>
                    <?php if (isLoggedIn()): ?>
                    <tr>
                        <td>User ID</td>
                        <td><?php echo $_SESSION['user_id']; ?></td>
                    </tr>
                    <tr>
                        <td>Username</td>
                        <td><?php echo isset($_SESSION['user_data']->username) ? $_SESSION['user_data']->username : 'Not set'; ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.diagnostics-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.diagnostics-card {
    background-color: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 20px;
    box-shadow: var(--box-shadow);
}

.diagnostics-card h2 {
    margin-top: 0;
    margin-bottom: 15px;
    font-size: 1.3rem;
    color: var(--accent-primary);
}

.diagnostics-table {
    width: 100%;
    border-collapse: collapse;
}

.diagnostics-table th, 
.diagnostics-table td {
    padding: 8px 10px;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

.diagnostics-table th {
    background-color: rgba(0,0,0,0.1);
}

.success-text {
    color: #2ecc71;
}

.warning-text {
    color: #f39c12;
}

.error-text {
    color: #e74c3c;
}

.success-message, .error-message {
    padding: 10px 15px;
    border-radius: 4px;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
}

.success-message {
    background-color: rgba(46, 204, 113, 0.1);
    border: 1px solid rgba(46, 204, 113, 0.3);
    color: #2ecc71;
}

.error-message {
    background-color: rgba(231, 76, 60, 0.1);
    border: 1px solid rgba(231, 76, 60, 0.3);
    color: #e74c3c;
}

.success-message i, .error-message i {
    margin-right: 10px;
    font-size: 1.2rem;
}

@media (max-width: 768px) {
    .diagnostics-container {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?>