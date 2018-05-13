<?php
/******************************************
* Database Connection Class
******************************************/
//$conn = new mysqli($hostname, $username, $password, $database);

class MySQLDatabase
{
    
    //Database Connection Config
    public $hostserver = 'localhost';
    public $username = 'root';
    public $password = '';
    public $database_name = 'user_db';
    

    private $connection;

	function __construct()
    {
		$this->open_connection();
	}

	public function open_connection()
    {
		$this->connection = mysqli_connect($this->hostserver, $this->username, $this->password, $this->database_name);
		if(mysqli_connect_errno())
        {
			die("Database Connection Have Failed".
			mysqli_connect_errno()." (".mysqli_connect_errno().")");
		}

	}

	public function close_connection()
    {
		if (isset($this->connection))
        {
			mysqli_close($this->connection);
			unset($this->connection);
		}
	}

    public function create_table($database_name)
    {
        $database = mysql_query("CREATE DATABASE $database_name");
        if(!$database)
        {
            die("Database Creation Have Failed!");
        }
    }

	public function query($sql)
    {
		$result = mysqli_query($this->connection, $sql);
		$this->confirm_query($result);
		return $result;
	}

	public function check_query($sql)
    {
		$result = mysqli_query($this->connection, $sql);
		return $result;
	}

	private function confirm_query($result)
    {
		if (!$result)
        {
			die("Database query failed");

		}
	}

	public function mysql_prep($string)
    {
		if (get_magic_quotes_gpc()) $string = stripslashes($string);
		return $this->connection->real_escape_string($string);

	}

	public function mysql_entities_fix_string($string)
    {
		return htmlentities($this->mysql_prep($string));
	}

	public function fetch_array($result)
    {
		return mysqli_fetch_array($result);
	}

	public function num_rows($result_set)
    {
		return mysqli_num_rows($result_set);
	}

	public function insert_id()
    {
		return mysqli_insert_id($this->connection);
	}

	public function affected_rows()
    {
		return mysqli_affected_rows($this->connection);
	}

	public function prepare_query($query)
    {
		return $this->connection->prepare($query);
	}

	public function get_post($var)
    {
		return $this->connection->real_escape_string($_POST[$var]);
	}

}

$database = new MySQLDatabase();

?>
