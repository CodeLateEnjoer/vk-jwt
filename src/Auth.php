<?php

class Auth {
    private User $user;
    private Jwt $jwt;
  
    public function __construct() 
    {
      $this->user = new User();
      $this->jwt = new Jwt();
    }
  
    public function register(string $email, string $password): array 
    {
      try {
        $result = $this->user->register($email, $password);
        return ['status' => 'success', 'data' => $result];
      } catch (Exception $e) {
        return ['status' => 'error', 'message' => $e->getMessage()];
      }
    }
  
    public function authorize(string $email, string $password): array 
    {
      $user = $this->user->getByEmail($email);
  
      if (!$user || !password_verify($password, $user['password'])) {
        return ['status' => 'error', 'message' => 'Invalid email or password'];
      }
  
      $token = $this->jwt->generateToken($user['id']);
  
      return ['status' => 'success', 'data' => ['access_token' => $token]];
    }
  }
  