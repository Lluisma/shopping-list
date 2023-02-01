<?php

namespace App;

use Exception;

class Product {

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
	 * Get all products
	 * @return type
	 */
	public function getProducts() {

		$stmt = $this->pdo->query('SELECT id, name, active, id_category FROM product');
		$products = array();
		while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
			$idCategory = $row['id_category'];
			if (!isset($products[$idCategory])) {
				$products[$idCategory] = array();
			}
			$products[$idCategory][] = $row;
		}
		return $products;
	}

	/**
	 * Get categories from selecte products
	 * @return type
	 */
	public function getSelectedCategories() {

		$stmt = $this->pdo->query('SELECT distinct id_category, active FROM product WHERE active = 2');
		$selcategories = array();
		while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
			$selcategories[] = $row['id_category'];
		}
		return $selcategories;
	}

	/**
	 * Insert a new product
	 * @param string $productName
	 * @return the id of the new product
	 */
	public function insertProduct($productName, $productCategory) {
		$sql = 'INSERT INTO product(name, id_category) VALUES(:product_name, :product_category)';
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':product_name', $productName);
		$stmt->bindValue(':product_category', $productCategory);
		$stmt->execute() or die(print_r($stmt->errorInfo(), true));
		return $this->pdo->lastInsertId();
	}

	/**
     * Activate/Deactivate the specified product
     * @param integer $productId
     * @param bool $active
     * @return bool true if success and false on failure
     */
    public function activateProduct($productId, $active) {
        // SQL statement to update status of a task to completed
        $sql = "UPDATE product "
                . "SET active = :active "
                . "WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $productId);
        $stmt->bindValue(':active', $active);
        return $stmt->execute();
    }

	/**
     * Deactivates all products
     * @return bool true if success and false on failure
     */
    public function deactivateProducts() {
        // SQL statement to update status of a task to completed
        $sql = "UPDATE product "
                . "SET active = 0 ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute();
    }


}