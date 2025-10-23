<?php
/**
 * Simple database connectivity check using the credentials stored in wp-config.php.
 */

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function extract_wp_db_constants(string $config_path): array {
    if (!is_readable($config_path)) {
        throw new RuntimeException("Unable to read wp-config.php at {$config_path}.");
    }

    $config = file_get_contents($config_path);
    if ($config === false) {
        throw new RuntimeException('Failed to load wp-config.php contents.');
    }

    $keys = ['DB_NAME', 'DB_USER', 'DB_PASSWORD', 'DB_HOST', 'DB_CHARSET'];
    $values = [];

    foreach ($keys as $key) {
        $pattern = "/define\\(\\s*'" . preg_quote($key, '/') . "'\\s*,\\s*'([^']*)'\\s*\\)\\s*;/";
        if (!preg_match($pattern, $config, $matches)) {
            throw new RuntimeException("Unable to locate {$key} in wp-config.php.");
        }
        $values[$key] = $matches[1];
    }

    return [
        'name'     => $values['DB_NAME'],
        'user'     => $values['DB_USER'],
        'password' => $values['DB_PASSWORD'],
        'host'     => $values['DB_HOST'],
        'charset'  => $values['DB_CHARSET'],
    ];
}

function check_database_connection(array $credentials): void {
    $mysqli = new mysqli(
        $credentials['host'],
        $credentials['user'],
        $credentials['password'],
        $credentials['name']
    );

    try {
        if (!empty($credentials['charset'])) {
            $mysqli->set_charset($credentials['charset']);
        }

        $result = $mysqli->query('SHOW TABLES');
        $tables = [];
        if ($result !== false) {
            while ($row = $result->fetch_row()) {
                $tables[] = $row[0];
            }
        }

        echo "Database connection successful.\n";
        if ($tables) {
            echo "Tables (" . count($tables) . "): \n - " . implode("\n - ", $tables) . "\n";
        } else {
            echo "No tables found or unable to list tables.\n";
        }
    } finally {
        $mysqli->close();
    }
}

try {
    $credentials = extract_wp_db_constants(__DIR__ . '/wp-config.php');
    check_database_connection($credentials);
} catch (Throwable $exception) {
    fwrite(STDERR, 'Database check failed: ' . $exception->getMessage() . "\n");
    exit(1);
}
