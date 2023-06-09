<?php
    namespace Models;
    class HouseType{
        protected static $conn;
        protected static $columnsTbl=['id_housetype','name_housetype'];
        private $id_housetype;
        private $name_housetype;
        public function __construct($args = []){
            $this->id_housetype = $args['id_housetype'] ?? '';
            $this->name_housetype = $args['name_housetype'] ?? '';
        }
        public function saveData($data){
            $delimiter = ":";
            $dataBd = $this->sanitizarAttributos();
            $valCols = $delimiter . join(',:',array_keys($data));
            $cols = join(',',array_keys($data));
            $sql = "INSERT INTO housetype ($cols) VALUES ($valCols)";
            $stmt= self::$conn->prepare($sql);
            $stmt->execute($data);
        }
        public function loadAllData(){
            $sql = "SELECT id_housetype,name_housetype FROM housetype";
            $stmt= self::$conn->prepare($sql);
            $stmt->execute();
            $housetypes = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $housetypes;
        }
        public function loadByIdData($id){
            $sql = "SELECT id_housetype,name_housetype FROM housetype WHERE id_housetype = :id_housetype";
            $stmt= self::$conn->prepare($sql);
            $stmt->bindParam(':id_housetype', $id, \PDO::PARAM_INT); 
            $stmt->execute();
            $country = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $country;
        }
        public function deleteByIdData($id){
            $response=[];
            $sql = "DELETE FROM housetype WHERE id_housetype = :id_housetype";
            $stmt= self::$conn->prepare($sql);
            $stmt->bindParam(':id_housetype', $id, \PDO::PARAM_INT); 
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
                if($columna === 'id_housetype') continue;
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