<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DAO extends CI_Model{
    function __construct(){
        parent::__construct();
    }

    function entitySelection($entityName, $whereClause = NULL, $isUnique = FALSE){
        if($whereClause){
            $this->db->where($whereClause);
        }
        $query = $this->db->get($entityName);
        if($this->db->error()['message']!=""){
            $reponseDB = array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>"Db error: ".$this->db->error()['message'],
                "validations"=>null,
                "data"=>null
            );
        }else{
            $reponseDB = array(
                "status"=>"success",
                "status_code"=>200,
                "message"=>"Data loaded, successful",
                "validations"=>null,
                "data"=>$isUnique ? $query->row() : $query->result()
            );
        }
        return $reponseDB;
    }

    function examplefecha($entityName,$whereClause,$data){
        $query = $this->db->query("SELECT * from ? WHERE ? = like %?%");
    }
    
    function selectEntity($entity,$params = null,$isUnique = TRUE){
        if($params){
            $this->db->where($params);
        }
        $query = $this->db->get($entity);
        if($this->db->error()['message']!=''){
            $response = array(
                "status"=>"error",
                "message"=>$this->db->error()['message'],
                "data"=>null
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=>"informacion cergada correctamente",
                "data"=>$isUnique ? $query->row() : $query->result()
            );
        }
        return $response;
    }

    function insertData($entityName,$data){
        $this->db->insert($entityName,$data);
        if($this->db->error()['message']!=""){
            $reponseDB = array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>"Db error: ".$this->db->error()['message'],
                "validations"=>null,
                "data"=>null
            );
        }else{
            $reponseDB = array(
                "status"=>"success",
                "status_code"=>201,
                "message"=>"Data inserted successful",
                "validations"=>null,
                "data"=>null
            );
        }
        return $reponseDB;
    }

    function updateData($entityName,$data,$whereClause){
        $this->db->where($whereClause);
        $this->db->update($entityName,$data);
        if($this->db->error()['message']!=""){
            $reponseDB = array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>"Db error: ".$this->db->error()['message'],
                "validations"=>null,
                "data"=>null
            );
        }else{
            $reponseDB = array(
                "status"=>"success",
                "status_code"=>200,
                "message"=>"Data updated successful",
                "validations"=>null,
                "data"=>null
            );
        }
        return $reponseDB;
    }

    function deleteData($entityName,$whereClause){
        $this->db->where($whereClause);
        $this->db->delete($entityName);
         if($this->db->error()['message']!=""){
            $reponseDB = array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>"Db error: ".$this->db->error()['message'],
                "validations"=>null,
                "data"=>null
            );
        }else{
            $reponseDB = array(
                "status"=>"success",
                "status_code"=>200,
                "message"=>"Data deleted successful",
                "validations"=>null,
                "data"=>null
            );
        }
        return $reponseDB;
    }

    function saveOrUpdateItem($entityName,$data,$whereClause = NULL,$generateKey =  FALSE){

        if($whereClause){
            $this->db->where($whereClause);
            $this->db->update($entityName,$data);
        }else{
            $this->db->insert($entityName,$data);
        }
        if($this->db->error()['message']!=''){
            $responseDB = array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>$this->db->error()['message']
            );
        }else{
            $responseDB = array(
                "status"=>"success",
                "status_code"=>$whereClause ? 200 : 201,
                "message"=>"Item created Successfully",
                "key"=>$generateKey ? $this->db->insert_id() : null
            );
        }
        return $responseDB;
    }

    function deleteClient($id){
        $this->db->trans_begin();

        $this->db->query('UPDATE clients SET  statusClient  = "Inactive" WHERE clients.idClient = ? ',array($id));


        if ($this->db->trans_status() === FALSE)
        {
                $this->db->trans_rollback();
                $responseDB = array(
                "status"=> "error",
                "status_code"=>409,
                "message"=>$this->db->error()['message'],
                "data"=>$null
                );

        }
        else
        {
                $this->db->trans_commit();
                $responseDB = array(
                "status"=> "success",
                "status_code"=>201,
                "message"=>"Person, client and user delete successfully",
                "data"=>null
            );
        }

        return $responseDB;
    }


    function deleteDriver($id){
        $this->db->trans_begin();

        $this->db->query('UPDATE drivers SET  statusDriver = "Inactive" WHERE drivers.idDriver = ? ',array($id));


        if ($this->db->trans_status() === FALSE)
        {
                $this->db->trans_rollback();
                $responseDB = array(
                "status"=> "error",
                "status_code"=>409,
                "message"=>$this->db->error()['message'],
                "data"=>$null
                );

        }
        else
        {
                $this->db->trans_commit();
                $responseDB = array(
                "status"=> "success",
                "status_code"=>201,
                "message"=>"Person, driver and user delete successfully",
                "data"=>null
            );
        }

        return $responseDB;
    }

    function change($id){
        $this->db->trans_begin();

        $this->db->query('UPDATE vehicles SET statusVehicle = "Asigned" WHERE vehicles.idVehicle = ? ',array($id));
        // $this->db->query("UPDATE Products SET stockProduct = stockProduct + ? WHERE barcodeProduct = ?",array($quantity,$code));

        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $responseDB = array(
                "status"=> "error",
                "status_code"=>409,
                "message"=>$this->db->error()['message'],
                "data"=>$null
                );

        }else{
                $this->db->trans_commit();
                $responseDB = array(
                "status"=> "success",
                "status_code"=>201,
                "message"=>"updated successfully",
                "data"=>null
            );
        }

        return $responseDB;
    }

    function changeContainer($id){
        $this->db->trans_begin();

        $this->db->query('UPDATE containers SET statusContainer = "Asigned" WHERE containers.idContainer = ? ',array($id));
        // $this->db->query("UPDATE Products SET stockProduct = stockProduct + ? WHERE barcodeProduct = ?",array($quantity,$code));

        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $responseDB = array(
                "status"=> "error",
                "status_code"=>409,
                "message"=>$this->db->error()['message'],
                "data"=>$null
                );

        }else{
                $this->db->trans_commit();
                $responseDB = array(
                "status"=> "success",
                "status_code"=>201,
                "message"=>"updated successfully",
                "data"=>null
            );
        }

        return $responseDB;
    }

    function changeDriver($id){
        $this->db->trans_begin();

        $this->db->query('UPDATE drivers SET statusDriver = "Asigned" WHERE drivers.idDriver = ? ',array($id));
        // $this->db->query("UPDATE Products SET stockProduct = stockProduct + ? WHERE barcodeProduct = ?",array($quantity,$code));

        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $responseDB = array(
                "status"=> "error",
                "status_code"=>409,
                "message"=>$this->db->error()['message'],
                "data"=>$null
                );

        }else{
                $this->db->trans_commit();
                $responseDB = array(
                "status"=> "success",
                "status_code"=>201,
                "message"=>"updated successfully",
                "data"=>null
            );
        }

        return $responseDB;
    }

    function changeDriverAvailable($id){
        $this->db->trans_begin();

        $this->db->query('UPDATE drivers SET statusDriver = "Available" WHERE drivers.idDriver = ? ',array($id));
        // $this->db->query("UPDATE Products SET stockProduct = stockProduct + ? WHERE barcodeProduct = ?",array($quantity,$code));

        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $responseDB = array(
                "status"=> "error",
                "status_code"=>409,
                "message"=>$this->db->error()['message'],
                "data"=>$null
                );

        }else{
                $this->db->trans_commit();
                $responseDB = array(
                "status"=> "success",
                "status_code"=>201,
                "message"=>"updated successfully",
                "data"=>null
            );
        }

        return $responseDB;
    }

    function cambiarMarcador($entityName,$data,$whereClause){
        $query = $this->db->query("UPDATE players SET scorePlayer = scorePlayer +1 WHERE idPlayer = ?");
    }

    function aumentarmarcador($entityName,$whereClause){
        $query = $this->db->query("UPDATE ? SET scorePlayer = scorePlayer +1 WHERE idPlayer = ?");
    }


}