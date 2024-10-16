<?php

namespace App\Entity;

use App\Repository\PassagerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PassagerRepository::class)]
class Passager extends User
{
/*    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;*/

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $preferences = null;


    public function getPreferences(): ?string
    {
        return $this->preferences;
    }

    public function setPreferences(?string $preferences): static
    {
        $this->preferences = $preferences;

        return $this;
    }
}
