<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
    	$this->loadMainCategories($manager);
    	$this->loadSubcategories($manager, 'Electronics');
    	$this->loadSubcategories($manager, 'Computers');
    	$this->loadSubcategories($manager, 'Laptops');
    	$this->loadSubcategories($manager, 'Books');
    	$this->loadSubcategories($manager, 'Movies');
    	$this->loadSubcategories($manager, 'Romance');
    }

    private function loadMainCategories(ObjectManager $manager){
    	foreach($this->getMainCategoriesData() as [$name]){
	        $category = new Category();
	        $category->setName($name);
	       	$this->addReference($name, $category);
	        $manager->persist($category);
    	}

        $manager->flush();    	
    }

    private function loadSubcategories(ObjectManager $manager, $categoryName){
    	$method = "get{$categoryName}Data";
    	foreach($this->$method() as [$name]){

	        $category = new Category();
	        $category->setName($name);
	        $category->setParent($this->getReference($categoryName));
	        $this->addReference($name, $category);
	        $manager->persist($category);
    	}

        $manager->flush();    	
    }

    private function getMainCategoriesData(){
    	return [
    		['Electronics', 1],
    		['Toys', 2],
    		['Books', 3],
    		['Movies',4],
    	];
    }

    private function getElectronicsData(){
    	return [
    		['Cameras', 5],
    		['Computers', 6],
    		['Cell Phones', 7],
    	];
    }

    private function getComputersData(){
    	return [
    		['Laptops', 5],
    		['Desktops', 6]
    	];
    }

    private function getLaptopsData()
    {
        return [

            ['Apple',10],
            ['Asus',11], 
            ['Dell',12], 
            ['Lenovo',13], 
            ['HP',14]

        ];
    }

    private function getBooksData()
    {
        return [
            ['Children\'s Books',15],
            ['Kindle eBooks',16], 
        ];
    }


    private function getMoviesData()
    {
        return [
            ['Family',17],
            ['Romance',18], 
        ];
    }


    private function getRomanceData()
    {
        return [
            ['Romantic Comedy',19],
            ['Romantic Drama',20], 
        ];
    }
}
