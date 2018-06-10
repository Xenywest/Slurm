<?php

/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 10.06.2018
 * Time: 20:24
 */
class Tokenization
{
    public $data = array();

    public $tokenized_data = array();

    private $isTokenized = false;

    private $jobs_id = array();
    /**
     * Tokenization constructor.
     * @param $raw_data array from exec()
     */
    public function __construct($raw_data)
    {
        $this->data = $raw_data;
    }

    public function tokenize()
    {
        unset($this->data[0]);
        $this->data = array_values($this->data);

        foreach ($this->data as $str)
        {
            $this->tokenized_data[] = preg_replace('/ +/', ' ', $str);
        }

        $this->isTokenized = true;
        $this->setAssocJobIDtoUsername();
    }

    private function setAssocJobIDtoUsername()
    {
        foreach($this->tokenized_data as $str)
        {
            $exploded_string = explode(' ', $str);
            $this->jobs_id[$exploded_string[C::JOB_ID]] = $exploded_string[C::USER_NAME];
        }
    }

    public function getJobsIDList()
    {
        return $this->isTokenized ? array_keys($this->jobs_id) : 'Tokenize first';
    }

    public function getAssocJobsIDtoUsername()
    {
        return $this->isTokenized ? $this->jobs_id : 'Tokenize first';
    }

    /**
     * @return array
     */
    public function getTokenizedData()
    {
        return $this->tokenized_data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

}