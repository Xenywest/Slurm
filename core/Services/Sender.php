<?php

/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 11.06.2018
 * Time: 1:46
 */
class Sender
{
    public function generateRequest($data)
    {

        $info = array();
        $iterator = 0;
        var_dump($data);
        /*foreach ($data as $raw)
        {
            $info[] = $raw;
            $iterator++;

            if($iterator === C::SEND_PER_GENERATE_LIMIT)
            {
                $info = json_encode($info);
                $this->send();

                $iterator = 0;
                $info = array();
            }

        }*/

       // json_encode($data);
    }


}