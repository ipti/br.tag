<?php

class PasswordHasher {
    
    /**
     * @param string $password
     * @return string hashed password
     */
    public function bcriptHash(string $password)
    {
        $cost = 10;
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]);
    }
}