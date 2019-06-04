<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordUpdate
{

    private $id;


    private $oldPassword;

    /**
     * @Assert\NotIdenticalTo(propertyPath="oldPassword", message = "Le nouveau mot d epasse doit être différent de votre ancien mot de passe")
     */
    private $newPassword;

    /**
    @Assert\EqualTo(propertyPath="newPassword", message = "Les 2 mots de passe doivent être identiques")
    */
    private $confirmPassword;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}
