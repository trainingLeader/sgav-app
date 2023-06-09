<?php
    namespace Models;
    class Region{
        protected static $conn;
        protected static $columnsTbl=['id_region','name_region','id_country'];
        private $id_region;
        private $name_region;
        private $id_country;
        public function __construct($args = []){
            $this->id_region = $args['id_region'] ?? '';
            $this->name_region = $args['name_region'] ?? '';
            $this->id_country = $args['id_country'] ?? '';
            
        }
        public function saveData($data){
            $delimiter = ":";
            $dataBd = $this->sanitizarAttributos();
            $valCols = $delimiter . join(',:',array_keys($data));
            $cols = join(',',array_keys($data));
            $sql = "INSERT INTO regions ($cols) VALUES ($valCols)";
            $stmt= self::$conn->prepare($sql);
            $stmt->execute($data);
        }
        public function loadAllData(){
            $sql = "SELECT id_region,name_region,id_country FROM regions";
            $stmt= self::$conn->prepare($sql);
            $stmt->execute();
            $regions = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $regions;
        }
        public function loadByIdData($id){
            $sql = "SELECT id_region,name_region,id_country FROM regions WHERE id_region = :id_region";
            $stmt= self::$conn->prepare($sql);
            $stmt->bindParam(':id_region', $id, \PDO::PARAM_INT); 
            $stmt->execute();
            $region = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $region;
        }
        public function deleteByIdData($id){ 
            $response=[];
            $sql = "DELETE FROM regions WHERE id_region = :id_region";
            $stmt= self::$conn->prepare($sql);
            $stmt->bindParam(':id_region', $id, \PDO::PARAM_INT); 
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
                if($columna === 'id_region') continue;
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