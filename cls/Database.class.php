<?

class Database{
	
	private $DB_ServerName;
	private $DB_DatabaseName;
	private $DB_UserName;
	private $DB_Password;
	
    private $starttime;


	function __construct() {
		
		if(isset($_SERVER['SERVER_NAME']) && ($_SERVER['SERVER_NAME'] == "ryans-macbook-pro.local")){
			//LOCAL//
			$this->DB_ServerName = "localhost";
			$this->DB_DatabaseName = "myCocktailor";
			$this->DB_UserName = "root";
			$this->DB_Password = "root";
		}else{
			//LIVE//
			$this->DB_ServerName = "localhost";
			$this->DB_DatabaseName = "myCocktailor";
			$this->DB_UserName = "XXXXX";
			$this->DB_Password = "XXXXX";
		}

	}
	
    
    protected function processTime(){
        $endtime = microtime(true);
        console($endtime - $this->starttime, -1); 
    }

    private function connect(){
	    
     //    $this->starttime = microtime(true);

	    try {
	        $dbTest = new PDO("mysql:host=".$this->DB_ServerName.";dbname=".$this->DB_DatabaseName."", $this->DB_UserName, $this->DB_Password);    	
	       	if (! $dbTest) {
	       		throw new Exception();
	       	}
	       	$dbTest = null;
	    } catch (PDOException $e) {
	        print "Error!: " . $e->getMessage() . "<br/>";
	        die();
	    }
	    
	    $this->dbh = new PDO("mysql:host=".$this->DB_ServerName.";dbname=".$this->DB_DatabaseName."", $this->DB_UserName, $this->DB_Password);
		
    }
    
    private function disconnect(){
    	$this->dbh = null;
    }
    
    
    // private function log($table, $action, $rowId){
    
    // 	$query = "INSERT INTO changeLog (user, action, tableName, rowId) VALUES (?, ?, ?, ?)";
    // 	$statement = $this->dbh->prepare($query);
    // 	$statement->execute(array("".isset($_SESSION['customs']['userId'])."", $action, $table, $rowId));
    // 	$this->disconnect();
    	
    // }

    
    private function truncate($table) {
    
    	$this->connect();
    	$query = "TRUNCATE TABLE $table";
    	$statement = $this->dbh->prepare($query);
    	$statement->execute();
    	//$this->log($table, "truncate", 'NA');
    	
    }
    
 
    protected function delete($table, $prams){   
    
        $this->connect();
        $query = "DELETE FROM $table WHERE ".$prams;
        $statement = $this->dbh->prepare($query);
        $statement->execute();
        //$this->log($table, "delete", $prams);
    }

 
    public function select($cols, $table, $join, $prams, $suffix){


        $this->connect();
        $prams = (isset($prams) ? " WHERE ".$prams : '');
        $suffix = (isset($suffix) ? $suffix : '');
        $query = "SELECT ".$cols." FROM ".$table.' '.$join.$prams;
        //echo $query;
        $statement = $this->dbh->prepare($query);
        $statement->execute();
        
        if(!isset($this->{$table.$suffix})){        
            $this->{$table.$suffix} = array();
        }
        
        while($row = $statement->fetchObject()){
            if (isset($row->id)) {
                $this->{$table.$suffix}[$row->id]=$row;
            }else{
                $this->{$table.$suffix}[]=$row;
            }
            
        }
        
        $this->disconnect();
    }

    public function flatSelect($col, $table, $prams, $suffix){


        $this->connect();
        $prams = (isset($prams) ? " WHERE ".$prams : $suffix);
        $suffix = (isset($suffix) ? $suffix : '');
        $query = "SELECT ".$col." FROM ".$table.' '.$prams;
        //echo $query;
        $statement = $this->dbh->prepare($query);
        $statement->execute();
        
        if(!isset($this->{$table.$suffix})){        
            $this->{$table.$suffix} = array();
        }
        
        while($row = $statement->fetchObject()){
            array_push($this->{$table.$suffix}, $row->$col);            
        }
        
        $this->disconnect();
    }
    

    public function exists($table, $prams){

        $this->connect();
        $query = "SELECT EXISTS(SELECT 1 FROM ".$table.' WHERE '.$prams. ' LIMIT 1)';
       // echo $query;
        $statement = $this->dbh->prepare($query);
        $statement->execute();

        $output = $statement->fetch();

        $itExists = ($output[0] > 0 ? true: false);
    
        $this->disconnect();

        return $itExists;
    }
    
    

    

    protected function insert($table, $names, $values, $multiRow){
    	
    	//print_r($values);
    	// die('
     //        '.$table.'
     //        '.$names.'
     //        ');
    	
    	if($multiRow  == NULL || $multiRow  == "START"){
    		$this->connect();
    	}
    	
    	$query = "INSERT INTO $table $names";
    	$statement = $this->dbh->prepare($query);
    	$statement->execute($values);
    	$insertId = $this->dbh->lastInsertId();
    	if($multiRow  == NULL || $multiRow  == "END"){
    		//$this->log($table, "insert", $insertId);
    	   // $this->processTime();
        }
        return $insertId;
    }  
        
    
    public function update($table, $names, $values, $id){   
        
    	$this->connect();
    	$query = "UPDATE $table SET $names WHERE id=?";
    	$statement = $this->dbh->prepare($query);
    	$statement->execute($values);
    	//$this->log($table, "update", $id);
    
    }
    
    
    public function formPush($task, $table, $formObj){ 

    	//$postArr = array("task"=>"update", "id"=>"45", "table"=>"users", "VFQ-username"=>"logTestXXX");
    	$executeValues = array();
    	
    	$insertQueryP1 = '';
    	$insertQueryP2 = '';
    	$updateQuery = '';
    	
    	foreach ($formObj as $key => $value) {
    		
    		if($key !== "id"){
    			
    			$insertQueryP1 = $insertQueryP1.$key.", ";
    			$insertQueryP2 = $insertQueryP2."?, ";
    			$updateQuery = $updateQuery.$key."=?, ";
    			array_push($executeValues, $value);
    		}
    	}
    	
    	$insertQueryP1 = rtrim($insertQueryP1, ', ');
    	$insertQueryP2 = rtrim($insertQueryP2, ', ');
    	$insertQuery = "(".$insertQueryP1.") VALUES (".$insertQueryP2.")";
    	$updateQuery = rtrim($updateQuery, ', ');
    	
    	if($task == "insert"){
    		$formObj["id"] = $this->insert($table, $insertQuery, $executeValues, NULL);
    		//array_push($_SESSION['noticeQueue'], array("update", "0", "Database", "New entry added to ".$table));
    	}else{
    		array_push($executeValues, $formObj["id"]);
    		$this->update($table, $updateQuery, $executeValues, $formObj["id"]);
    		//array_push($_SESSION['noticeQueue'], array("update", "0", "Database", $table." updated"));
    	}

        $this->select('id, '.$insertQueryP1, $table, '', "id=".$formObj["id"], '');

        return $this->$table;
    	
    }
    
    // public function logArchive() {
    // 	$this->select('*', 'changeLog', '', 'id!=0', '');
    // 	file_put_contents('httpdocs/archive/logs/log-'.date("YM", strtotime("-1 month")).'.json', json_encode($this->changeLog));
    // 	$this->truncate("changeLog");
    // }
 		
}


//USAGE REMINDERS

//$db = new Database();

//$db->delete("users", "id=96");

//$db->select("username", "users", "", "id!=99", "1");

//echo $db->users1[0]->username;

?>