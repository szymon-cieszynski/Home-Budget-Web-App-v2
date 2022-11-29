<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 */
class User extends \Core\Model
{
        /**
     * Error messages
     *
     * @var array
     */
    public $errors = []; //tablica z błędami

    /**
     * Class constructor
     *
     * @param array $data  Initial property values
     *
     * @return void
     */
    public function __construct($data = []) //konstruktor który tworzy uruchamia się podczas tworzenia obiektu w kontrolerze i przpyisuje mu dane
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public function save()
    {

       // $this->validate();

       

            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            //$token = new Token();
           // $hashed_token = $token->getHash();
            //$this->activation_token = $token->getValue();

            $sql = 'INSERT INTO users (username, password_hash, email/*, activation_hash*/)
                    VALUES (:username, :password_hash, :email/*, :activation_hash*/)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':username', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            //$stmt->bindValue(':activation_hash', $hashed_token, PDO::PARAM_STR);

            $stmt->execute();
        

       // return false;
        
    }
}
