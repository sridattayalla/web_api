<?php

class Project{
    // database connection and table name
    private $conn;
    private $table_name = "projects";

    // object properties
    public $user;
    public $name;
    public $description;
    public $content;

    public function __construct($db, $cuser){
        $this->conn = $db;
        $this->user = $cuser;
    } 

    function exist(){
        $query = "SELECT * FROM projects WHERE user_id= :user_id and project_name= :name LIMIT 0,1";

        //preapare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->content=htmlspecialchars(strip_tags($this->content));

        //add params
        $stmt->bindParam(':user_id', $this->user->id);
        $stmt->bindParam(':name', $this->name);

       $num = 0;

        if($stmt->execute()){
             // get number of rows
            $num = $stmt->rowCount();
        }
        

        if($num>0){
            return true;
        }else{
            return false;
        }
    }

    function createProject(){
        $query = "INSERT INTO ".$this->table_name ." SET user_id = :user_id,
        project_name = :name,
        descreption = :descreption,
                content = :content";
        
        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->content=htmlspecialchars(strip_tags($this->content));
        
        // bind the values
        $stmt->bindParam(':user_id', $this->user->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':descreption', $this->description);
        $stmt->bindParam(':content', $this->content);

        if($stmt->execute()){
            return true;
        }

        return false;
    }

}