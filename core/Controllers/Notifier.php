<?php

/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 10.06.2018
 * Time: 20:21
 */
class Notifier extends Controller
{
    public function validate($command_line)
    {
        // TODO: Implement isValidInput() method.
    }

    public function action()
    {
        $lexic_analyzer = new Tokenization(Squeue::execute());
        $lexic_analyzer->tokenize();

        $assoc_lexic = $lexic_analyzer->getAssocJobsIDtoUsername();

        //!TODO change to get all with status != FINISHED and NOTIFIED
        $assoc_sqldata = Job::all(array('condition' => array('status = ?', C::JOB_ADDED/*'1'*/)));

        //два раза сортировка, приделать флаг, типа если сначала с скл перебором по лексеру не нашли, значит задание выполнено
        //а второй раз если лексером перебором по скл не нашли, значит нужно добавить задание

        /*если в списке с консоли нет задания в списке бд
        И если в списке с консоли имя пользователя отсутствует в таблице соотношений, то не добавлять задание в БД*/

        $this->CheckFromSQLtoConsole($assoc_lexic,$assoc_sqldata);
        $this->CheckFromConsoletoSQL($assoc_lexic, $assoc_sqldata);

        //Notify here
        $this->Notify();

    }

    private function Notify()
    {
        $jobs_finished = Job::all(array('condition' => array('status = ?', C::JOB_FINISHED/*'2'*/)));

        $sender = new Sender();
        $sender->generateRequest($jobs_finished);
    }

    /**
     * Getting data from SQL table and then starts compare with console data
     * If RECORD in SQL exists, but in CONSOLE does not exists => it means JOB is done, need to NOTIFY
     * @param $assoc_lexic array
     * @param $assoc_sqldata array
     */
    private function CheckFromSQLtoConsole($assoc_lexic, $assoc_sqldata)
    {
        foreach ($assoc_sqldata as $raw)
        {
            $job_found = C::FALSE;

            foreach ($assoc_lexic as $job_id => $username)
            {
                if($raw['job_id'] == $job_id)
                {
                    $job_found = C::TRUE;
                    break;
                }
            }

            //TASK DONE
            if($job_found === false)
            {
                $completed_job = Job::find($raw['job_id']);
                $completed_job->status = C::DONE;
                $completed_job->save();
            }
        }
    }

    /**
     * Getting data from CONSOLE and then starts compare with SQL data
     * If RECORD in Lexic exists, but in SQL does not exists => it means new JOB added, need to insert into SQL
     * @param $assoc_lexic array
     * @param $assoc_sqldata array
     */
    private function CheckFromConsoletoSQL($assoc_lexic, $assoc_sqldata)
    {
        foreach ($assoc_lexic as $job_id => $username)
        {
            //существует ли в бд соотношение пользователя
            if(Users::isExists($username))
            {
                $job_found = C::FALSE;

                foreach ($assoc_sqldata as $raw)
                {
                    if ($raw['job_id'] == $job_id)
                    {
                        $job_found = C::TRUE;
                        break;
                    }
                }

                if ($job_found === false)
                {
                    $new_job = new Job();
                    $new_job->job_id = $job_id;
                    $new_job->username = $username;
                    $new_job->status = C::NEW;
                    $new_job->save();
                }
            }
        }
    }


}