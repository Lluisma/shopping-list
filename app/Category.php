<?php

namespace App;


class Category {

	/**
	 * PDO object
	 * @var \PDO
	 */
	private $pdo;

	/**
	 * Initialize the object with a specified PDO object
	 * @param \PDO $pdo
	 */
	public function __construct($pdo) {
			$this->pdo = $pdo;
	}

	/**
	 * Get all categories
	 * @return type
	 */
	public function getCategories() {

		$stmt = $this->pdo->query('SELECT id, name FROM category');
		$categories = [];
		while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
			$categories[ $row['id'] ] = $row['name'];
		}
		return $categories;
	}

}