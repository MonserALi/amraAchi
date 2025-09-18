<?php
class Auth
{
  private $db;

  public function __construct()
  {
    $this->db = new Database;
  }

  public function login($email, $password)
  {
    $this->db->query("SELECT * FROM users WHERE email = :email");
    $this->db->bind(':email', $email);

    $row = $this->db->single();

    if ($row) {
      $hashed_password = $row->password;
      if (password_verify($password, $hashed_password)) {
        // Get user role
        $this->db->query("SELECT r.name FROM roles r 
                                JOIN user_roles ur ON r.id = ur.role_id 
                                WHERE ur.user_id = :user_id");
        $this->db->bind(':user_id', $row->id);
        $role = $this->db->single();

        // Set session
        Session::init();
        Session::set('login', true);
        Session::set('user_id', $row->id);
        Session::set('user_email', $row->email);
        Session::set('user_name', $row->name);
        Session::set('user_role', $role->name);

        return true;
      }
    }

    return false;
  }

  public function register($data)
  {
    $this->db->query("INSERT INTO users (name, email, password, phone, date_of_birth, gender, blood_group) 
                          VALUES (:name, :email, :password, :phone, :date_of_birth, :gender, :blood_group)");

    // Bind values
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
    $this->db->bind(':phone', $data['phone']);
    $this->db->bind(':date_of_birth', $data['date_of_birth']);
    $this->db->bind(':gender', $data['gender']);
    $this->db->bind(':blood_group', $data['blood_group']);

    // Execute
    if ($this->db->execute()) {
      // Get the inserted user ID
      $user_id = $this->db->lastInsertId();

      // Assign patient role
      $this->db->query("INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, 1)");
      $this->db->bind(':user_id', $user_id);
      $this->db->execute();

      return true;
    }

    return false;
  }

  public function logout()
  {
    Session::init();
    Session::destroy();
  }

  public function isLoggedIn()
  {
    Session::init();
    return Session::get('login');
  }

  public function getUserRole()
  {
    Session::init();
    return Session::get('user_role');
  }

  public function getUserId()
  {
    Session::init();
    return Session::get('user_id');
  }
}
