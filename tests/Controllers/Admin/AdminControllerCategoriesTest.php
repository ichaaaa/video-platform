<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Category;
use App\Tests\RoleAdmin;

class AdminControllerCategoriesTest extends WebTestCase
{

    use RoleAdmin;

    public function testTextOnPage()
    {
        
        $crawler = $this->client->request('GET', '/admin/su/categories');

       // $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Categories list', $crawler->filter('h2')->text());
        $this->assertContains('Electronics', $this->client->getResponse()->getContent());
    }

    public function testNumberOfItems()
    {
    	$crawler = $this->client->request('GET', '/admin/su/categories');
    	$this->assertCount(21, $crawler->filter('option'));
    }

    public function testNewCategory()
    {
    	$crawler = $this->client->request('GET', '/admin/su/categories');
    	$form = $crawler->selectButton('Add')->form([
    		'category[parent]'=>1,
    		'category[name]'=>'Other Electronics',
    	]);

    	$this->client->submit($form);
    	$category = $this->entityManager->getRepository(Category::class)->findOneBy(['name'=>'Other Electronics']);
    	$this->assertNotNull($category);
    	$this->assertSame('Other Electronics', $category->getName());
    }

    public function testEditCategory()
    {
    	$crawler = $this->client->request('GET', '/admin/su/edit-category/5');

    	$form = $crawler->selectButton('Save')->form([
    		'category[parent]'=>1,
    		'category[name]'=>'Cameras 2',
    	]);

		$this->client->submit($form);
    	$category = $this->entityManager->getRepository(Category::class)->find(5);

		$this->assertSame('Cameras 2', $category->getName());
    }

    public function testDeleteCategory()
    {
		$crawler = $this->client->request('GET', '/admin/su/delete-category/8');
		$category = $this->entityManager->getRepository(Category::class)->find(8);
		$this->assertNull($category);
    }
}
