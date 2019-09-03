<?php

namespace App\Tests;

use App\Entity\Comment;
use App\Tests\Rollback;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontControllerCommentsTest extends WebTestCase
{
	use Rollback;

	public function testNotLoggedInUser()
	{
		$client = static::createClient();
		$client->followRedirects();

		$crawler = $client->request('GET', '/video-details/16');
		$form = $crawler->selectButton('Add')->form([
			'comment'=>'Test comment',
		]);

		$client->submit($form);

		$this->assertContains('Please sign in', $client->getResponse()->getContent());
	}

	public function testNewCommentAndNumberOfComments()
	{
		$this->client->followRedirects();
		$crawler = $this->client->request('GET', '/video-details/16');

		$form = $crawler->selectButton('Add')->form([
			'comment'=>'Test comment'
		]);
		$this->client->submit($form);

    	$comment = $this->entityManager->getRepository(Comment::class)->findOneBy(['content'=>'Test comment']);
		$this->assertNotNull($comment);
		//$crawler = $this->client->request('GET', '/video-list/category/toys,2');

		//$this->assertSame('Comments (2)', $crawler->filter('a.ml-1')->text());
	}
}
