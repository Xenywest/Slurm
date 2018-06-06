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
    public function isValidInput($command_line)
    {
        // TODO: Implement isValidInput() method.
    }

    public function action()
    {
        $lexic_analyzer = new Tokenization(Squeue::execute());
        $lexic_analyzer->tokenize();

        //!TODO compare tokenized data and SQL data
    }
}

class AddRelate extends Controller
{
    public function isValidInput($input)
    {
        if(\strlen($input[C::USER_CLUSTER]) > C::USER_MAX_CHARS)
        {
            return false;//C::ERR_LONG;
        }

        //!TODO add chat_id check
        return true;
    }

    public function action()
    {
        $statement = $this->getDatabase()->getConnection()->prepare(
            'INSERT INTO userlist ("username_cluster", "user_messager_token") VALUES (":username", ":token")'
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
    public $database;

    public $arguments;

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

        if($this->isValidInput($this->arguments))
        {
            $this->action();
        }
    }

    abstract public function isValidInput($command_line);

    abstract public function action();

    public function getDatabase()
    {
        return $this->database;
    }

    public function getArguments()
    {
        return $this->arguments;
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

    public $isTokenized = false;

    private $jobs_id = array();
    /**
     * Tokenization constructor.
     * @param $raw_data array
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