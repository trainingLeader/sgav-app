<?php
    namespace Models;
    class Country{
        protected static $conn;
        protected static $columnsTbl=['id_country','name_country'];
        private $id_country;
        private $name_country;
        public function __construct($args = []){
            $this->id_country = $args['id_country'] ?? '';
            $this->name_country = $args['name_country'] ?? '';
        }
        public function saveData($data){
            $delimiter = ":";
            $dataBd = $this->sanitizarAttributos();
            $valCols = $delimiter . join(',:',array_keys($data));
            $cols = join(',',array_keys($data));
            $sql = "INSERT INTO countries ($cols) VALUES ($valCols)";
            $stmt= self::$conn->prepare($sql);
            try {
                $stmt->execute($data);
                $response=[[
                    'id_country' => self::$conn->lastInsertId(),
                    'name_country' => $data['name_country']
                ]];

            }catch(\PDOException $e) {
                echo $sql . "<br>" . $e->getMessage();
            }

            return $response;
        }
        public function loadAllData(){
            $sql = "SELECT id_country,name_country FROM countries";
            $stmt= self::$conn->prepare($sql);
            $stmt->execute();
            $countries = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $countries;
        }
        public function loadByIdData($id){
            $sql = "SELECT id_country,name_country FROM countries WHERE id_country = :id_country";
            $stmt= self::$conn->prepare($sql);
            $stmt->bindParam(':id_country', $id, \PDO::PARAM_INT); 
            $stmt->execute();
            $country = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $country;
        }
        public function deleteByIdData($id){
            $response=[];
            $sql = "DELETE FROM countries WHERE id_country = :id_country";
            $stmt= self::$conn->prepare($sql);
            $stmt->bindParam(':id_country', $id, \PDO::PARAM_INT); 
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
                if($columna === 'id_country') continue;
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