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
        var_dump($data);
        foreach ($data as $raw)
        {
            $info[] = array($raw->job_id => $raw->user_messager_token);

            //!TODO send limit to prevent too MANY data send
            /*if($iterator === C::SEND_PER_GENERATE_LIMIT)
            {
                $this->prepareSend($info);

                $iterator = 0;
                $info = array();
            }*/

        }
        $this->prepareSend($info);
    }


    private function prepareSend($info)
    {
        $info = json_encode($info);
        var_dump($info);
        //$this->send();
    }


}