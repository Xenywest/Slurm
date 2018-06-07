<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 06.06.2018
 * Time: 22:21
 */
$arguments = $_SERVER['argv'];

$app = new Application($arguments);
$app->start();


class Application
{
    public $command_arguments = array();

    public function __construct($arguments)
    {
        $this->command_arguments = $arguments;
    }

    public function start()
    {
        if(Security::isWhitelisted($this->command_arguments[C::COMMAND]))
        {
            $controller = Helper::getController($this->command_arguments);
            $controller::initialize($this->command_arguments);
        }
        else
        {
            return 'refuse';
        }
    }
}

class C
{
    const COMMAND = 1;
}


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
        $assoc_sqldata = Job::getAll();

        //два раза сортировка, приделать флаг, типа если сначала с скл перебором по лексеру не нашли, значит задание выполнено
        //а второй раз если лексером перебором по скл не нашли, значит нужно добавить задание

        /*если в списке с консоли нет задания в списке бд
        И если в списке с консоли имя пользователя отсутствует в таблице соотношений, то не добавлять задание в БД*/

        $this->CheckFromSQLtoConsole($assoc_lexic,$assoc_sqldata);
        $this->CheckFromConsoletoSQL($assoc_lexic, $assoc_sqldata);

        //Notify here

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

class Config
{
    public static function getDB()
    {
        return 'sqlite:' . __DIR__ . 'sqlite.db';
    }

}


class DBManager
{
    public $connection;
    public function __construct()
    {
        $this->connection = new PDO(Config::getDB());
    }

    public function getConnection()
    {
        return $this->connection;
    }
}



abstract class Controller
{
    private $database;

    public $arguments;

    //!TODO move it AWAY to Model class
    public function __construct()
    {
        $this->database = new DBManager();
    }

    public static function initialize($command_line)
    {
        $class = new static;
        return $class->service($command_line);
    }

    public function service($command_line)
    {
        $this->arguments = $command_line;

        if($this->validate($this->arguments))
        {
            $this->action();
        }
    }

    abstract public function validate($command_line);

    abstract public function action();

    public function getDatabase()
    {
        return $this->database;
    }

}

class Squeue
{
    public static function execute()
    {
        $output = array();
        exec('squeue', $output);
        return $output;
    }
}


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
            $this->jobs_id[$exploded_string[C::JobID]] = $exploded_string[C::Username];
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