<?php 

// src/Form/DataTransformer/IssueToNumberTransformer.php
namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FrenchToDateTimeTransformer implements DataTransformerInterface
{

// on passe les données au formulaire
    public function transform($date)
    {
        if ($date == null) return "";
        return $date -> format ('d/m/Y');
    }

// reçoit les données du formulaire
    public function reverseTransform($frenchDate)
    {
    	$date = \DateTime::createFromFormat('d/m/Y', $frenchDate);
    	$date -> setTime(0,0);
    	return $date;
    }
}


?>