<?php

namespace App\Models;

use PDO;
use \App\Token;

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

        $this->validate();

       if(empty($this->errors))
       {
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

        return $stmt->execute();
       }

       return false;
        
    }

    /**
     * Validate current property values, adding valiation error messages to the errors array property
     *
     * @return void
     */
    public function validate()
    { //walidacja danych podczas rejestracji
        // Name
        if ($this->name == '') {
            $this->errors[] = 'Name is required';
        }

        // email address
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Invalid email';
        }
        if (static::emailExists($this->email, $this->id ?? null)) {
            $this->errors[] = 'email already taken';
        }

        // Password, dajemy jednak przycisk show
        //  if ($this->password != $this->password_confirmation) {
        //      $this->errors[] = 'Password must match confirmation';
        //  }

        // Password - walidujemy tylko wtedy gdy jest wpisane - np. podczas edycji profilu nie musimy go walidować przecież..
        if (isset($this->password)) {

            if (strlen($this->password) < 6) {
                $this->errors[] = 'Please enter at least 6 characters for the password';
            }
            //wyrazenie regularne jako sprawdzenie hasła 
            if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
                $this->errors[] = 'Password needs at least one letter';
            }

            if (preg_match('/.*\d+.*/i', $this->password) == 0) {
                $this->errors[] = 'Password needs at least one number';
            }
        }
    }
    //do zapytania Ajax nie tworzymy jeszcze obiektu więc metoda musi być public static
     public static function emailExists($email)
    {

        return static::findByEmail($email) !== false; //jeśli będzie pusty fetch czyli nic nie znajdzie to zwróci false

    }

    /**
     * Find a user model by email address
     *
     * @param string $email email address to search for
     *
     * @return mixed User object if found, false otherwise
     */
    public static function findByEmail($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

        /**
     * Authenticate a user by email and password.
     *
     * @param string $email email address
     * @param string $password password
     *
     * @return mixed  The user object or false if authentication fails
     */
    public static function authenticate($email, $password)
    {
        $user = static::findByEmail($email); //spr najpierw czy email istnieje

        if ($user /*&& $user->is_active*/) {
            if (password_verify($password, $user->password_hash)) {
                return $user;
            }
        }

        return false;
    }

        /**
     * Find a user model by ID
     *
     * @param string $id The user ID
     *
     * @return mixed User object if found, false otherwise
     */
    public static function findByID($id)
    {
        $sql = 'SELECT * FROM users WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

        /**
     * Remember the login by inserting a new unique token into the remembered_logins table
     * for this user record
     *
     * @return boolean  True if the login was remembered successfully, false otherwise
     */
    public function rememberLogin()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->remember_token = $token->getValue();

        $this->expiry_timestamp = time() + 60 * 60 * 24 * 30;  // 30 days from now ale mozna mzienic na inna 60 sek * 60 min * 24h * 30dni

        $sql = 'INSERT INTO remembered_logins (token_hash, user_id, expires_at)
                VALUES (:token_hash, :user_id, :expires_at)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expiry_timestamp), PDO::PARAM_STR);

        return $stmt->execute();
    }
}
