<?php
class Model
{
  protected $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  // Get all records from a table
  public function all($table)
  {
    $this->db->query("SELECT * FROM " . $table);
    return $this->db->resultSet();
  }

  // Find a record by ID
  public function find($table, $id)
  {
    $this->db->query("SELECT * FROM " . $table . " WHERE id = :id");
    $this->db->bind(':id', $id);
    return $this->db->single();
  }

  // Create a new record
  public function create($table, $data)
  {
    $fields = implode(', ', array_keys($data));
    $values = ':' . implode(', :', array_keys($data));

    $sql = "INSERT INTO " . $table . " (" . $fields . ") VALUES (" . $values . ")";
    $this->db->query($sql);

    foreach ($data as $key => $value) {
      $this->db->bind(':' . $key, $value);
    }

    return $this->db->execute();
  }

  // Update a record
  public function update($table, $id, $data)
  {
    $set = '';
    foreach ($data as $key => $value) {
      $set .= $key . ' = :' . $key . ', ';
    }
    $set = rtrim($set, ', ');

    $sql = "UPDATE " . $table . " SET " . $set . " WHERE id = :id";
    $this->db->query($sql);

    $this->db->bind(':id', $id);
    foreach ($data as $key => $value) {
      $this->db->bind(':' . $key, $value);
    }

    return $this->db->execute();
  }

  // Delete a record
  public function delete($table, $id)
  {
    $sql = "DELETE FROM " . $table . " WHERE id = :id";
    $this->db->query($sql);
    $this->db->bind(':id', $id);
    return $this->db->execute();
  }

  // Query with custom SQL
  public function query($sql)
  {
    return $this->db->query($sql);
  }

  // Bind values
  public function bind($param, $value, $type = null)
  {
    return $this->db->bind($param, $value, $type);
  }

  // Execute query
  public function execute()
  {
    return $this->db->execute();
  }

  // Get single result
  public function single()
  {
    return $this->db->single();
  }

  // Get multiple results
  public function resultSet()
  {
    return $this->db->resultSet();
  }

  // Get row count
  public function rowCount()
  {
    return $this->db->rowCount();
  }

  // Get last insert ID
  public function lastInsertId()
  {
    return $this->db->lastInsertId();
  }
}
