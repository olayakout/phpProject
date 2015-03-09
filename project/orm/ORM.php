<?php
require 'appconf.php';

class ORM {

    static $conn;
    private $dbconn;
    protected $table;

    static function getInstance(){
        if(self::$conn == null){
            self::$conn = new ORM();
        }
        return self::$conn;
    }
    
    protected function __construct(){    
        extract($GLOBALS['conf']);
        $this->dbconn = new mysqli($host, $username, $password, $database);
    }
    
    function getConnection(){
        return $this->dbconn;
    }
    
    function setTable($table){
        $this->table = $table;
        //var_dump($this->table);
    }

     function insert($data){
        $query = "insert into $this->table set ";
        foreach ($data as $col => $value) {
            $query .= $col."= '".$value."', ";   
        }
        $query[strlen($query)-2]=" ";
        //var_dump($query);
        $state = $this->dbconn->query($query);
        if(! $state){
            return $this->dbconn->error;
        }
        
        return $this->dbconn->affected_rows;   
    }
    

    function select($colums,$data){

        $query = "select ";
        foreach ($colums as $colum) {
            $query .=$colum." ,";   
        }

        $query[strlen($query)-1]=" ";
        //var_dump($query);

        $query.="from $this->table where ";
       //var_dump($query);
        foreach ($data as $col => $value) {
            $query .= $col."= '".$value."' and ";   
        }
        
        $query=split(" ",$query);
        unset($query[count($query)-1]);
        unset($query[count($query)-1]);

        $query=implode(" ",$query);

        //var_dump($query);

        $result = mysqli_query($this->dbconn,$query);

        if(!$this->dbconn->affected_rows){
            return $this->dbconn->error;
        }
        else{
        for($i=0; $i < $this->dbconn->affected_rows ; $i++){
            $row = mysqli_fetch_assoc($result);
            
             $result= array_values($row);
             $results[]=$result;
            //var_dump($result);
        } 
        //var_dump($results);
        return $results;
       }
    }

    function selectAll(){
    	$query = "select * from $this->table";
    	$result = mysqli_query($this->dbconn,$query);
        if(! $result){
            return $this->dbconn->error;
        }
        
        for($i=0; $i < $this->dbconn->affected_rows ; $i++){
        	$row = mysqli_fetch_assoc($result);
		//$user = array_values($row);
		$users[]=$row; 
        } 
        return $users;
    
    }

     function Order(){
        $query = "select * from $this->table order by date desc";
        $result = mysqli_query($this->dbconn,$query);
        if(! $result){
            return $this->dbconn->error;
        }
        
        for($i=0; $i < $this->dbconn->affected_rows ; $i++){
            $row = mysqli_fetch_assoc($result);
        //$user = array_values($row);
        $users[]=$row; 
        } 
        return $users;
    
    }
    

   function fill($data){ //select some fields for all records 
    	$fields=implode(" , ",$data);
    	$query="select $fields from $this->table";
        //var_dump($query);
    	$state = $this->dbconn->query($query);
	$result = mysqli_query($this->dbconn,$query);
	if($this->dbconn->affected_rows==0){
		return $this->dbconn->error;
	} 
	else{
		for($i=0; $i < $this->dbconn->affected_rows ; $i++){
			$row = mysqli_fetch_assoc($result); 
			$users[]=$row; 
		} 
		return $users; 
	}   
    }


   function delete($id){ 
   	$query = "delete from $this->table where id=";
   	$query .=$id.';'; 
   	$state = $this->dbconn->query($query);
   	//return $query; 
   	if(! $state){
            return $this->dbconn->error;
        }
        
        return $this->dbconn->affected_rows;  
   	}

    function update($data,$where){ 
     $query="update $this->table set ";
     foreach ($data as $col => $value) {
            $query .= $col."= '".$value."', ";   
        }
        $query[strlen($query)-2]=" ";
        //var_dump($query);
        $query.=" where ";
        foreach ($where as $col => $value) {
            $query .= $col."= '".$value."' and ";   
        }
         //var_dump($query);
       
        $query=split(" ",$query);
        unset($query[count($query)-1]);
        unset($query[count($query)-1]);

        $query=implode(" ",$query);

        //var_dump($query);

        $result = mysqli_query($this->dbconn,$query);
        //var_dump($result);
        if(!$result){
            return $this->dbconn->error;
        }
     
        return $result;
       

    }

    function query($query){ 
        $results=[];
        $result= mysqli_query($this->dbconn,$query);
        if($this->dbconn->affected_rows==0){
         return $this->dbconn->error;
          } else{ 
            for($i=0; $i < $this->dbconn->affected_rows ; $i++){
             $row = mysqli_fetch_assoc($result);
             $results[]=$row;
              }
             return $results;
              }
         }

    

 }


