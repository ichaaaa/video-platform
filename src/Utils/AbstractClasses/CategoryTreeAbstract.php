<?php

namespace App\Utils\AbstractClasses;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class CategoryTreeAbstract
{
	public $categoriesArrayFromDb;
	public $categoryList;
	protected static $dbConnection;

	public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator)
	{
		$this->entityManager = $entityManager;
		$this->urlGenerator = $urlGenerator;
		$this->categoriesArrayFromDb = $this->getCategories();
	}

	abstract public function getCategoryList(array $categoriesArray);

	public function buildTree(int $parentId = null): array
	{
		$subcategory = [];
		foreach($this->categoriesArrayFromDb as $category)
		{
			if($category['parent_id'] == $parentId)
			{
				$children = $this->buildTree($category['id']);
				if($children){
					$category['children'] = $children;
				}
				$subcategory[] = $category;
			}
		}
		return $subcategory;
	}

	private function getCategories(): array
	{
		if(self::$dbConnection)
		{
			return self::$dbConnection;
		}else
		{
			$conn = $this->entityManager->getConnection();
			$sql = "SELECT * FROM categories";
			$stmt = $conn->prepare($sql);
			$stmt->execute();

			return self::$dbConnection = $stmt->fetchAll();
		}
	}

}

?>