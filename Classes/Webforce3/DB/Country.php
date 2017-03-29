<?php

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;
use Classes\Webforce3\Exceptions\InvalidSqlQueryException;

class Country extends DbObject {
    /** @var string */
    protected $name;

    public function __construct($id=0, $name='') {
        $this->name = $name;

        parent::__construct($id);
    }

    // =====================================================
    // this function will select all countries and
    // list them in the dropdown menu
    // =====================================================
    /**
	 * @return array
	 * @throws InvalidSqlQueryException
	 */
    public static function getAllForSelect() {
        $returnList = array();

        $request = '
            SELECT cou_id, cou_name
            FROM country
            ORDER BY cou_name ASC
        ';

        $stmt = Config::getInstance()->getPDO()->prepare($request);
		if ($stmt->execute() === false) {
			throw new InvalidSqlQueryException($request, $stmt);
		}
		else {
			$allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			foreach ($allDatas as $row) {
				$returnList[$row['cou_id']] = $row['cou_name'];
			}
		}

        return $returnList;
    }

    // =====================================================
    // selects cou_name based on cou_id
    // =====================================================
    /**
	 * @param int $id
	 * @return bool|Student
	 * @throws InvalidSqlQueryException
	 */
    public static function get($id) {
        $request = '
            SELECT cou_id, cou_name
            FROM country
            WHERE cou_id = :id
            ORDER BY cou_name ASC
        ';

        $stmt = Config::getInstance()->getPDO()->prepare($request);
		$stmt->bindValue(':id', $id, \PDO::PARAM_INT);

		if ($stmt->execute() === false) {
			throw new InvalidSqlQueryException($request, $stmt);
		}
		else {
			$row = $stmt->fetch(\PDO::FETCH_ASSOC);
			if (!empty($row)) {
				$currentObject = new Country (
					$row['cou_id'],
					$row['cou_name']
				);
				return $currentObject;
			}
		}
    }

    // =====================================================
    // this function allows us to delete countries
    // =====================================================
    /**
	 * @param int $id
	 * @return bool
	 * @throws InvalidSqlQueryException
	 */
    public static function deleteById($id) {
        $sql = '
			DELETE FROM country WHERE cou_id = :id
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		$stmt->bindValue(':id', $id, \PDO::PARAM_INT);

		if ($stmt->execute() === false) {
			print_r($stmt->errorInfo());
		}
		else {
			return true;
		}
		return false;
    }


    // =====================================================
    // this function accesses the database and saves
    // added countries in it
    // =====================================================
    /**
	 * @return DbObject[]
	 * @throws InvalidSqlQueryException
	 */
    public static function getAll() {
        $returnList = array();

		$sql = '
			SELECT cou_id, cou_name
			FROM country
			WHERE cou_id > 0
			ORDER BY cou_name ASC
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		if ($stmt->execute() === false) {
			throw new InvalidSqlQueryException($sql, $stmt);
		}
		else {
			$allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			foreach ($allDatas as $row) {
				$currentObject = new Country (
					$row['cou_id'],
					$row['cou_name']
				);
				$returnList[] = $currentObject;
			}
		}

		return $returnList;
	}


    // =====================================================
    // this function accesses the database and saves
    // added countries in it
    // =====================================================
    /**
	 * @return bool
	 * @throws InvalidSqlQueryException
	 */
    public function saveDB() {
        if ($this->id > 0) {
			$sql = '
				UPDATE country
				SET cou_name = :name,
				WHERE cou_id = :id
			';
			$stmt = Config::getInstance()->getPDO()->prepare($sql);
			$stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
			$stmt->bindValue(':name', $this->name, \PDO::PARAM_INT);


			if ($stmt->execute() === false) {
				throw new InvalidSqlQueryException($sql, $stmt);
			}
			else {
				return true;
			}
		}
		else {
			$sql = '
				INSERT INTO country (cou_name)
				VALUES (:name)
			';
			$stmt = Config::getInstance()->getPDO()->prepare($sql);
			$stmt->bindValue(':name', $this->name);

			if ($stmt->execute() === false) {
				throw new InvalidSqlQueryException($sql, $stmt);
			}
			else {
				$this->id = Config::getInstance()->getPDO()->lastInsertId();
				return true;
			}
		}

		return false;
    }

    // =====================================================
    // getters
    // =====================================================
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }


}

 ?>
