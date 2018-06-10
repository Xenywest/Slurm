<?php

/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 10.06.2018
 * Time: 20:22
 */
class AddRelate extends Controller
{
    public function validate($input)
    {
        if(\strlen($input[C::USER_CLUSTER]) > C::USER_MAX_CHARS)
        {
            return false;//C::ERR_LONG;
        }

        //!TODO add chat_id check and existance
        return true;
    }

    public function action()
    {
        $statement = $this->getDatabase()->getConnection()->prepare(
            'INSERT INTO userlist ("username_cluster", "user_messager_token") VALUES (":username" , ":token")'
        );

        $statement->bindParam(':username', $this->arguments[C::USER_CLUSTER_NAME]);
        $statement->bindParam(':token', $this->arguments[C::USER_TOKEN]);
        $statement->execute();

        //!TODO add OUT SUCCESSFUL
    }
}
