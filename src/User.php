<?php

class User {
    private Database $db;
  
    public function __construct() 
    {
      $this->db = new Database();
    }
  
    public function register(string $email, string $password): array
    {
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email');
      }
  
      if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $password)) {
        throw new Exception('Weak password');
      }
  
      $stmt = $this->db->getConnection()->prepare("SELECT * FROM users WHERE email = :email");
      $stmt->execute(['email' => $email]);
  
      if ($stmt->rowCount() > 0) {
        throw new Exception('Email already exists');
      }
  
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
  
      $stmt = $this->db->getConnection()->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
      $stmt->execute(['email' => $email, 'password' => $hashedPassword]);
  
      $userId = $this->db->getConnection()->lastInsertId();
  
      return ['user_id' => $userId, 'password_check_status' => 'good'];
    }
  
    public function getById(int $id) 
    {
      $stmt = $this->db->getConnection()->prepare("SELECT * FROM users WHERE id = :id");
      $stmt->execute(['id' => $id]);
  
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }
  
    public function getByEmail(string $email) 
    {
      $stmt = $this->db->getConnection()->prepare("SELECT * FROM users WHERE email = :email");
      $stmt->execute(['email' => $email]);
  
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }
  }
  