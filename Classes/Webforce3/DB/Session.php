<?php

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;

class Session extends DbObject {
	protected $startDate;
	protected $endDate;
	protected $sessionNumber;
	protected $locationId;
	protected $trainingId;

	public function __construct($id=0, $startDate='', $endDate='', $sessionNumber=0, $inserted='', $locationId=0, $trainingId=0) {
		$this->startDate = $startDate;
		$this->endDate = $endDate;
		$this->sessionNumber = $sessionNumber;
		$this->locationId = $locationId;
		$this->trainingId = $trainingId;
		
		parent::__construct($id, $inserted);
	}





	/**
	 * @param int $id
	 * @return DbObject
	 */
	public static function get($id) {
		$sql = '
			SELECT ses_id, ses_start_date, ses_end_date, ses_number, ses_inserted, location_loc_id, training_tra_id
			FROM session
			WHERE ses_id = :id
		';

		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		$stmt->bindValue(':id', $id, \PDO::PARAM_INT);

		if ($stmt->execute() === false) {
			throw new InvalidSqlQueryException($sql, $stmt);
		}
		else {
			$row = $stmt->fetch(\PDO::FETCH_ASSOC);
			if (!empty($row)) {
				$currentObject = new Session (
					$row['ses_id'],
					$row['ses_start_date'],
					$row['end_start_date'],
					$row['ses_inserted'],
					$row['location_loc_id'],
					$row['training_tra_id']
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
		$returnList = array();

		$sql = '
			SELECT ses_id, ses_start_date, ses_end_date, ses_number, ses_inserted, location_loc_id, training_tra_id
			FROM session
			WHERE ses_id > 0
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		if ($stmt->execute() === false) {
			throw new InvalidSqlQueryException($sql, $stmt);
		}
		else {
			$allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			foreach ($allDatas as $row) {
				$currentObject = new Session (
					$row['ses_id'],
					$row['ses_start_date'],
					$row['end_start_date'],
					$row['ses_inserted'],
					$row['location_loc_id'],
					$row['training_tra_id']
				);
				$returnList[] = $currentObject;
			}
		}

		return $returnList;
	}




	/**
	 * @return array
	 */
	public static function getAllForSelect() {
		$returnList = array();

		$sql = '
			SELECT ses_id, tra_name, ses_start_date, ses_end_date, loc_name
			FROM session
			LEFT OUTER JOIN training ON training.tra_id = session.training_tra_id
			LEFT OUTER JOIN location ON location.loc_id = session.location_loc_id
			WHERE ses_id > 0
			ORDER BY ses_start_date ASC
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		if ($stmt->execute() === false) {
			print_r($stmt->errorInfo());
		}
		else {
			$allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			foreach ($allDatas as $row) {
				$returnList[$row['ses_id']] = '['.$row['ses_start_date'].' > '.$row['ses_end_date'].'] '.$row['tra_name'].' - '.$row['loc_name'];
			}
		}

		return $returnList;
	}





	/**
	 * @param int $sessionId
	 * @return DbObject[]
	 */
	public static function getFromSession($sessionId) {
		// TODO: Implement getFromTraining() method.
	}





	/**
	 * @return bool
	 */
	public function saveDB() {
		// TODO: Implement saveDB() method.
	}





	/**
	 * @param int $id
	 * @return bool
	 */
	public static function deleteById($id) {
		$sql = '
			DELETE FROM session WHERE ses_id = :id
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

}
