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
        var_dump($input);
        if(\count($input) < 4 || \count($input) > 4)
        {
            //few args
            Logger::log("Error\nSyntax: php app.php add <username> <token>");
            return false;
        }

        if(\strlen($input[C::USER_CLUSTER_NAME]) > C::USER_MAX_CHARS)
        {

            Logger::log("Error\nUsername too long");
            return false;//C::ERR_LONG;
        }

        //!TODO add token check and existance
        if(User::exists(array('conditions' => array('username_cluster=?', $input[C::USER_CLUSTER_NAME]))))
        {
            Logger::log("Error\nUser already exists");
            return false;
        }

        return true;
    }

    public function action()
    {

        $user = new User();
        $user->username_cluster = $this->arguments[C::USER_CLUSTER_NAME];
        $user->user_messager_token = $this->arguments[C::USER_TOKEN];
        $user->save();

        echo 'saved';
        //!TODO add OUT SUCCESSFUL
    }
}
