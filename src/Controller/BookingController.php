<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    /**
     * @Route("/ads/{slug}/book", name="booking_create")
     * @IsGranted ("ROLE_USER")
     */
    public function book(Ad $ad,Request $request,ObjectManager $manager, BookingRepository $repo)
    {
        // initie le tabeau avec les dates non dispo
        $notAviableDays=[];
        // on récupère toutes les résa existante
        $nbr_resa = $repo ->findBy(array('ad'=>$ad->getId()));
        
        foreach ($nbr_resa as $value) {
            $tous_les_timestamps = range($value->getStartDate()->getTimestamp(),$value->getEndDate()->getTimestamp(),24*60*60);
            //dump($tous_les_timestamps);
            $notAviableDays = array_merge($notAviableDays, $tous_les_timestamps);
        }
        //dump($nbr_resa);

        $notAviableDays = array_unique($notAviableDays);
        $notAviableDays = array_values($notAviableDays);

        dump($notAviableDays);


        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $this -> getUser();
            $booking -> setBooker($user)
            -> setAd($ad)
            -> setCreatedAt(new \DateTime());

            // début interval date de calcule du prix
            $interval = date_diff($booking->getStartDate(), $booking->getEndDate());

            $booking -> setAmount($ad->getPrice()*$interval->days);
            // fin intzrval de calcul du prix

            $tous_les_timestamps_reservation = range($booking->getStartDate()->getTimestamp(),$booking->getEndDate()->getTimestamp(),24*60*60);

            dump($tous_les_timestamps_reservation);
            //die();

            $dateOK = true;

            foreach ($tous_les_timestamps_reservation as $value) {
                if (array_search($value, $notAviableDays)) {$dateOK = false; break;}
            }

            if (!$dateOK) {
                $this -> addFlash(
                    'warning',
                    'Les dates de réservations ne sont pas disponibles'
                );
            }

            else {
                    $manager ->persist($booking);
                    $manager ->flush();        
                return $this->redirectToRoute("booking_show",['id'=>$booking->getId()]);
            }



        }

        return $this->render('booking/book.html.twig', [
            'ad' => $ad,
            'form' => $form -> createView(),
            'notAviableDays' => $notAviableDays,


        ]);
    }


    /**
     * @Route("/booking/{id}", name="booking_show")
     * @IsGranted ("ROLE_USER")
     */
    public function show(Booking $booking)
    {

        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
        ]);

    }


}