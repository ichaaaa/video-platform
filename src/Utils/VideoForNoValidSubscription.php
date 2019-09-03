<?php
namespace App\Utils;

use App\Entity\Video;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;

class VideoForNoValidSubscription
{
	public $isSubscriptionValid = false;

	public function __construct(Security $security)
	{
		$user = $security->getUser();
		if($user && $user->getSubscription() != null)
		{
			$payment_status = $user->getSubscription()->getPaymentStatus();
			$valid = new \DateTime() < $user->getSubscription()->getValidTo();

			if($payment_status != null && $valid)
			{
				$this->isSubscriptionValid = true;
			}
		}
	}

	public function check()
	{
		if($this->isSubscriptionValid)
		{
			return null;
		}
		else
		{
			static $video = Video::videoForNoLoggedInOrNoMembers;
			return $video;
		}
	}
}