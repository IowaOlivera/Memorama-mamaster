<?php
/**
 * Created by IntelliJ IDEA.
 * User: jonathaneduardo
 * Date: 09/04/2016
 * Time: 02:37 PM
 */

require_once("DataBaseManager.php");

class MateriasManager{
    private $dbManager;
    private static $_instance;

    private function __construct(){
        $this->dbManager = DataBaseManager::getInstance();
    }

    public function __destruct(){
        /*
         * Falla cuando se llama a la funcion close();
         * */
        //$this->dbManager->close();
        self::$_instance = null;
    }

    public static function getInstance(){
        if(self::$_instance == null){
            self::$_instance = new MateriasManager();
        }
        return self::$_instance;
    }

    public function getMateria($idmateria){
        $query = "SELECT * FROM materias WHERE id = $idmateria";

        $resultado = $this->dbManager->realizeQuery($query);

        if($resultado == null){
            return "No se encontró esa materia";
        }
        else{
            if(is_array($resultado)){
                //echo "Método 1: getMateria(@Param: idmateria) First @return: ".$resultado; //Aqui
                return json_encode($resultado);
            }
            else{
                //echo "Método 1: getMateria(@Param: idmateria) Second @return: ".$resultado->num_rows;
                return $resultado->num_rows;
            }
        }
    }

    public function setMateria($id,$name){
        $query = "INSERT INTO materias (id, nombre) VALUES (".intval($id).",'$name')";

        $resultado = $this->dbManager->insertQuery($query);

        if($resultado == null){
            return $resultado;
        }else{
            return $resultado;
        }
    }


    public function updateMateria($id,$name){
        $query = "UPDATE materias set nombre= '$name' WHERE id =".intval($id);

        $resultado = $this->dbManager->insertQuery($query);

        if($resultado == null){
            //echo "Método 3: updateMateria(@Param: id, name) First @return: ".$resultado;
            return $resultado;
        }else{
            return false;
        }
    }

    public function deleteMateria($idMateria){
        $query = "DELETE FROM materias WHERE id = '$idMateria'";

        $resultado = $this->dbManager->insertQuery($query);

        if(is_bool($resultado)){
            //echo "Método 4: deleteMateria(@Param: idMateria) First @return: ".$resultado;
            return $resultado;
        }else{
            return false;
        }
        
    }

    public function getAllMateria(){
        $query = "SELECT * FROM materias";

        $resultado = $this->dbManager->realizeQuery($query);

        if($resultado == null){
            //echo "Método 5: getAllMaterias(@Param:) First @return: "."tabla materia vacia";
            return "tabla materia vacia";
        }
        else{
            if(is_array($resultado)){
                $matterList[] = $this->setValuesToResult($resultado);
                $value = json_encode($matterList);
                //echo "Método 5: getAllMaterias(@Param:) Second @return: ".$value;
                //console.log("hola mundo");
                
                return $value;
            }
            else{
               // echo "Método 5: getAllMaterias(@Param:) Third @return: ".$resultado->num_rows;
                return $resultado->num_rows;
            }
        }
    }
    
    private function setValuesToResult($result){
        $matter = array();
        for ($i=0;$i<count($result);$i++) {
            $matter['id'] = $result[$i]['id'];
            $matter['name'] = $result[$i]['nombre'];

            $matterList[] = $matter;

        }
       // echo "Método 6: setValuesToResult(@Param: result) @return: ".$matterList;
        return $matterList;
    }
}