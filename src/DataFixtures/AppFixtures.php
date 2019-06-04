<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Image;
use App\Entity\Role;
use App\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class AppFixtures extends Fixture
{

	private $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder)
	{
		$this->encoder=$encoder;
	}


    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

    	$adminRole = new Role ();
    	$adminRole -> setTitle ('ROLE_ADMIN');
    	$manager -> persist($adminRole);

    	$adminUser = new User();
    	$adminUser -> setFirstName ('Antoine')
    	-> setLastName ('Couvret')
    	-> setEmail ('antoine.couvret@gmail.com')
    	-> setHash ($this->encoder->encodePassword($adminUser, "password"))
    	->setPicture('https://picsum.photos/id/237/69')
    	->setIntroduction('Intro Antoine')
    	->setDescription('Description Antoine')
    	->setSlug('antoine_couvret')
    	->addUserRole($adminRole);
    $manager -> persist($adminUser);



	$slugify = new Slugify();
	$title = "Titre de l'annonce n°";

	for ($k=1; $k <5 ; $k++)
	{
		// Ajout des users
		$user = new User();
		$user->setSlug($slugify->slugify("prenom$k-nom$k"))
		->setFirstName("prenom$k")
		->setLastName("nom$k")
		->setPicture("https://via.placeholder.com/64")
		->setEmail("test$k@tesT.fr")
		->setIntroduction("introduction$k")
		->setDescription("description$k");

 		$encoded = $this->encoder->encodePassword($user, "pass");

		$user->setHash($encoded);

		$manager->persist($user);



		// Ajout d'annonces
		for ($i=1; $i <=mt_rand(1,5) ; $i++) {	
			$ad= new Ad();
			$ad->setTitle("$title$i.$k")
			->setSlug($slugify->slugify($ad->getTitle()))
			->setCoverImage("https://via.placeholder.com/350")
			
			->setIntroduction("C'est une introduction de l'annonce n°$i")
			->setContent("<p>Je suis le contenu de l'annonce n°$i</p>")
			->setPrice(mt_rand(25,500))
			->setRooms(mt_rand(1,5))
			->setAuthor($user);

		// AJOUT d'image
		for($j=1; $j<=mt_rand(1,5); $j++) {
				$image = new Image();
				$image->setUrl("https://via.placeholder.com/500x250")
				->setCaption("Légende de mon image A$i")
				->setAd($ad);

				$manager->persist($image);
				}
		
		// Ajout d'option de résa
		for($j=1; $j<=mt_rand(1,5); $j++) {
			$booking = new Booking();
			$booking -> setCreatedAt(new \DateTime())
			-> setStartDate(new \DateTime("+5 days"))
			-> setEndDate(new \DateTime("+15 days"))
			-> setAmount($ad->getPrice()*10)
			-> setComment("commentaire de la réservation  $j")
			-> setAd($ad)
			-> setBooker($user);
			$manager -> persist ($booking);
		}


		$manager->persist($ad);
		}




	} 







        $manager->flush();
    }
}
