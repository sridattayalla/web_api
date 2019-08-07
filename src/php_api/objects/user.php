<?php

class User{
    // database connection and table name
    private $conn;
    private $table_name = "employees";
 
    // object properties
    public $id;
    public $name;
    public $email;
    public $password;

    //constructor
    public function __construct($db){
        $this->conn = $db;
    }

    //create new user record
    function create(){
        //insert query
        $query = "INSERT INTO ".$this->table_name ." SET name = :name,
                email = :email,
                password = :password";
        
        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));
        
        // bind the values
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);

        //hash password before save
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);

        // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        }
    
        return false;
        }

        function emailExists(){
            $query = "SELECT * FROM ".$this->table_name." WHERE email=? LIMIT 0,1";

            //preapare query
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->email=htmlspecialchars(strip_tags($this->email));

            // bind given email value
            $stmt->bindParam(1, $this->email);

            // execute the query
            $stmt->execute();
        
            // get number of rows
            $num = $stmt->rowCount();

            if($num>0){
                // get record details / values
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // assign values to object properties
                $this->id = $row['id'];
                $this->name = $row['name'];
                $this->password = $row['password'];

                return true;
            }else{
                return false;
            }
        }

        function getProjects(){
            $query = "SELECT * FROM projects WHERE user_id = :user_id";
    
            //prepare query
            $stmt = $this->conn->prepare($query);
    
            //bind values
            $stmt->bindParam(":user_id", $this->id);
            
            if($stmt->execute()){
                // get number of rows
                $num = $stmt->rowCount();

                if($num>0){
                    $projects_array = array();
                    $projects_array["projects"] = array();
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        $project = array(
                            'name' => $row['project_name'],
                            'description' => $row['descreption'],
                            'content' => $row['content']
                        );
        
                        array_push($projects_array["projects"], $project);
                    }
        
                    return $projects_array;
                }else{
                    return false;
                }
                
            }
    
            return false;
    
        }
}