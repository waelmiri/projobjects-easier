<?php

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;

class City extends DbObject {
	/**
	 * @param int $id
	 * @return DbObject
	 */
    /** @var string */
    protected $name;
    /** @var country */    
    protected $country;
    
    function __construct($id=0,$country=null,$name='',$inserted='') {
        
        if(empty($country)){
            
            $this->country = new Country();               
        }
        else{
            $this->country = $country;
        }
        $this->name = $name;    
        
        parent::__construct($id, $inserted);
    }
    
    
    
    
        
        public static function get($id) {
		// TODO: Implement get() method.
            		$sql = '
			SELECT *
			FROM city
                        WHERE cit_id = :id;
                    ';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		$stmt->bindValue(':id', $id, \PDO::PARAM_INT);

		if ($stmt->execute() === false) {
			throw new InvalidSqlQueryException($sql, $stmt);
		}
		else {
			$row = $stmt->fetch(\PDO::FETCH_ASSOC);
			if (!empty($row)) {
				$currentObject = new city(
					$row['cit_id'],
                                        new Country($row['country_cou_id']),
                                        $row['cit_name']					
				);
				return $currentObject;
			}
		}
		return false;
        }

	/**
	 * @return DbObject[]
	 */
	public static function getAll() {
		// TODO: Implement getAll() method.
	}

	/**
	 * @return array
	 */
	public static function getAllForSelect() {
		$returnList = array();

		$sql = '
			SELECT cit_id, cit_name
			FROM city
			WHERE cit_id > 0
			ORDER BY cit_name ASC
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		if ($stmt->execute() === false) {
			print_r($stmt->errorInfo());
		}
		else {
			$allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			foreach ($allDatas as $row) {
				$returnList[$row['cit_id']] = $row['cit_name'];
			}
		}

		return $returnList;
	}

	/**
	 * @return bool
	 */
	public function saveDB() {
		// TODO: Implement saveDB() method.
            if($this->id > 0){
                $sql ='
                        UPDATE city
                        SET cit_name = :name
                        country_cou_id = :couId
                        WHERE cit_id = :id
                    
                    ';
                $stmt = Config::getInstance()->getPDO()->prepare($sql);
                $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
                $stmt->bindValue(':couId', $this->id, \PDO::PARAM_INT);
                $stmt->bindValue(':name', $this->name);
                
                if($stmt->execute() === false){
                    throw new \InvalidArgumentException($sql,$stmt);
                }
                else{
                    return true;
                }
            }
            else{
                $sql = '
                    INSERT INTO city(cit_name, country_cou_id)
                    VALUES (:name , :couId)
               '; 
               $stmt = Config::getInstance()->getPDO()->prepare($sql);
               $stmt->bindValue(':name', $this->name);
               $stmt->bindValue(':couId', $this->id, \PDO::PARAM_INT);
               
               if($stmt->execute() === false){
                   throw new \InvalidArgumentException($sql,$stmt);
               }
               else{
                   $this->id = Config::getInstance()->getPDO()->lastInsertId();
                   return true;
                }
            }
            return false;
	}

	/**
	 * @param int $id
	 * @return bool
	 */
	public static function deleteById($id) {
		// TODO: Implement deleteById() method.
            $sql = '
                DELETE FROM city WHERE cit_id = :id
               ';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindVlaue(':id', $id, \PDO::PARAM_INT);
            
            if($stmt->execute() === false){
                print_r($stmt->errorInfo());
            }
            else{
                return true;
            }
            return false;
                 
	}
        
        function getCountry() {
            return $this->country;
        }

        function setCountry($country) {
            $this->country = $country;
        }
        function getName() {
            return $this->name;
        }

        function setName($name) {
            $this->name = $name;
        }


     

}