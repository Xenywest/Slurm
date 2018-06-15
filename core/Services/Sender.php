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
        return $this->send($info);
    }


    private function prepareData($info)
    {
        return base64_encode(json_encode($info));
    }

    private function send($info)
    {
        $prepared_info = $this->prepareData($info);
        return $this->sendHTTP($prepared_info);
    }

    private function sendHTTP($request)
    {
        $ch = curl_init();
        $post_fields = array('data'=>$request);
        curl_setopt($ch, CURLOPT_URL,Config::WEBSITE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);

        curl_close ($ch);

        return $server_output;
    }


}