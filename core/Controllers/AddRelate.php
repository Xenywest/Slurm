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
        if(\count($input) < 4)
        {
            //few args
            echo 'few args';
            return false;
        }

        if(\strlen($input[C::USER_CLUSTER_NAME]) > C::USER_MAX_CHARS)
        {

            echo 'too long';
            return false;//C::ERR_LONG;
        }

        //!TODO add token check and existance
        if(User::exists(array('conditions' => array('username_cluster=?', $input[C::USER_CLUSTER_NAME]))))
        {
            //already has got universal token

            echo 'already exists';
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
