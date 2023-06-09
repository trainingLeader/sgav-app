<?php
    namespace Models;
    class Person{
        protected static $conn;
        protected static $columnsTbl=['id_person','firstname_person','lastname_person','birthdate_person','id_city'];
        private $id_person;
        private $firstname_person;
        private $lastname_person;
        private $birthdate_person;
        private $id_city;
        public function __construct($args = []){
            $this->id_person = $args['id_person'] ?? '';
            $this->firstname_person = $args['firstname_person'] ?? '';
            $this->lastname_person = $args['lastname_person'] ?? '';
            $this->birthdate_person = $args['birthdate_person'] ?? '';
            $this->id_city = $args['id_city'] ?? '';
            
        }
        public function saveData($data){
            $delimiter = ":";
            $dataBd = $this->sanitizarAttributos();
            $valCols = $delimiter . join(',:',array_keys($data));
            $cols = join(',',array_keys($data));
            $sql = "INSERT INTO persons ($cols) VALUES ($valCols)";
            $stmt= self::$conn->prepare($sql);
            $stmt->execute($data);
        }
        public function loadAllData(){
            $sql = "SELECT id_person,firstname_person,lastname_person,birthdate_person,id_city FROM persons";
            $stmt= self::$conn->prepare($sql);
            $stmt->execute();
            $persons = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $persons;
        }
        public function loadByIdData($id){
            $sql = "SELECT id_person,firstname_person,lastname_person,birthdate_person,id_city FROM persons WHERE id_person = :id_person";
            $stmt= self::$conn->prepare($sql);
            $stmt->bindParam(':id_person', $id, \PDO::PARAM_INT); 
            $stmt->execute();
            $person = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $person;
        }
        public function deleteByIdData($id){
            $response=[];
            $sql = "DELETE FROM persons WHERE id_person = :id_person";
            $stmt= self::$conn->prepare($sql);
            $stmt->bindParam(':id_person', $id, \PDO::PARAM_INT); 
            $stmt->execute();
            if ($stmt->rowCount()>0){
                $response=[[
                    'mensaje' => 'El registro fue eliminado correctamente',
                    'codEstado' => '200',
                    'totalreg' => $stmt->rowCount()
                ]];
            }else{
                $response=[[
                    'mensaje' => 'El registro no fue eliminado',
                    'reject' => 'Registro no encontrado o no existe',
                    'codEstado' => '204',
                    'totalreg' => $stmt->rowCount()
                ]];
            }
            return $response;
        }
        public static function setConn($connBd){
            self::$conn = $connBd;
        }
        public function atributos(){
            $atributos = [];
            foreach (self::$columnsTbl as $columna){
                $atributos [$columna]=$this->$columna;
             }
             return $atributos;
        }
        public function sanitizarAttributos(){
            $atributos = $this->atributos();
            $sanitizado = [];
            foreach($atributos as $key => $value){
                $sanitizado[$key] = self::$conn->quote($value);
            }
            return $sanitizado;
        }
    }
?>