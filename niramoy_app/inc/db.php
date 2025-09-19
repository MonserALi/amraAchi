<?php
require_once __DIR__ . '/config.php';

function get_db()
{
  global $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS;
  static $pdo = null;
  if ($pdo === null) {
    $dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4";
    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
  }
  return $pdo;
}

function json_response($data, $status = 200)
{
  http_response_code($status);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($data, JSON_UNESCAPED_UNICODE);
  exit;
}

/**
 * Check if a column exists in a table for the current database.
 * Returns true if exists, false on error or not found.
 */
function columnExists(PDO $pdo, $table, $column)
{
  try {
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table AND COLUMN_NAME = :column');
    $stmt->execute([':table' => $table, ':column' => $column]);
    return (bool)$stmt->fetchColumn();
  } catch (Exception $e) {
    return false;
  }
}
