<?php

namespace App\Entity;

use App\Repository\FeaturedImageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FeaturedImageRepository::class)
 */
class FeaturedImage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $source;

    // /**
    //  * @ORM\OneToOne(targetEntity=Trip::class, inversedBy="featuredImage", cascade={"persist", "remove"})
    //  */
    // private $trip;

     /**
     * @ORM\OneToOne(targetEntity=Trip::class, inversedBy="featuredImage", cascade={"persist"})
     */
    private $trip;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getTrip(): ?Trip
    {
        return $this->trip;
    }

    public function setTrip(?Trip $trip): self
    {
        $this->trip = $trip;

        return $this;
    }
}
