<?php
namespace App\front_office\Models;

Class UserModel extends \App\DbConnect\Model
{
    /**
     * Verify if the e-mail exists and if the password matches with the user e-mail and get the informations from database
     * @param  string $email    the e-mail of the user
     * @param  string $password the password of the user
     * @return bool|array   It returns the informations of the user if the informations are ok or false if they're not
     */
	public function getUser(string $email, string $password)
	{
		$requser = $this->pdo->prepare("SELECT * FROM user WHERE email = ?");
        $requser->execute(array($email));
        $userexist = $requser->fetch();
        if($userexist)
        {
            if(password_verify($password, $userexist['password'])) //to the left there is the password insert by the user and to the right there is the password of the database, if they are identicals the user will be connected.
            {
                return $userexist;
            }
        }

        return false;
	}


    /**
     * Verify if the e-mail exists and get the informations from database
     * @param  string $email    the e-mail of the user
     * @return bool|array     It returns the email address of the user if the informations are ok or false if they're nots
     */
    public function getEmail(string $email)
    {
        $requser = $this->pdo->prepare("SELECT email FROM user WHERE email = ?");
        $requser->execute(array($email));
        $userexist = $requser->fetch();
        if($userexist)
        {
                return true;
        }

        return false;
    }

    /**
     * It get the token that need to reset the password from the database
     * @param string $reset_token
     * @return bool It returns true if success and false if failure
     */
    public function getToken(string $reset_token):bool
    {
        $requser = $this->pdo->prepare("SELECT reset_token FROM user WHERE id = 1 AND reset_token = ?");
        $requser->execute(array($reset_token));
        $userexist = $requser->fetch();
        if($userexist)
        {
            return true;
        }
        return false;
    }

    /**
     * It updates the Token in the database that needs to reset the password
     * @param string $reset_token
     * @param string $email
     * @return bool it returns true if success and false if falure
     */
    public function resetToken(string $reset_token, string $email):bool
    {
        $resetToken = $this->pdo->prepare('UPDATE user SET reset_token = :reset_token WHERE email= :email');
        $req = $resetToken->execute(array(
            ':reset_token' => $reset_token,
            ':email' => $email,
        ));

        return $req;
    }

    /**
     * It allows to update the password in the database
     * @param  string $email    email of the user
     * @param  string $password password of the user
     * @return bool   it returns true if success and false if failure
     */
    public function changePassword(string $email, string $password):bool
    {
        $requser = $this->pdo->prepare('UPDATE user SET password = :password WHERE email = :email');
        
        $requser->bindValue(':email', $email);
        $requser->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
        
        return $requser->execute();

    }


    /**
     * it update the password in the database
     * @param string $newPassword
     * @return bool it returns true if success and false if failure
     */
    public function resetPassword(string $newPassword):bool
    {
        $requser = $this->pdo->prepare('UPDATE user SET password = :password, reset_token = NULL WHERE id = 1');

        $requser->bindValue(':password', password_hash($newPassword, PASSWORD_DEFAULT));

        return $requser->execute();
    }
}
