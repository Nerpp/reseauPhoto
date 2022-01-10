<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;
use App\Repository\TripRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity(repositoryClass=TripRepository::class)
 */
class Trip
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * 
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="trip")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Photo::class, mappedBy="trip", orphanRemoval=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $folder;

    /**
     * @ORM\OneToOne(targetEntity=FeaturedImage::class, mappedBy="trip", cascade={"persist", "remove"})
     */
    private $featuredImage;

   
 
    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->photo = new ArrayCollection();
        $this->featuredImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhoto(): Collection
    {
        return $this->photo;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photo->contains($photo)) {
            $this->photo[] = $photo;
            $photo->setTrip($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photo->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getTrip() === $this) {
                $photo->setTrip(null);
            }
        }

        return $this;
    }

    public function getFolder(): ?string
    {
        return $this->folder;
    }

    public function setFolder(string $folder): self
    {
        $this->folder = $folder;

        return $this;
    }

    public function getFeaturedImage(): ?FeaturedImage
    {
        return $this->featuredImage;
    }

    public function setFeaturedImage(?FeaturedImage $featuredImage): self
    {
        // unset the owning side of the relation if necessary
        if ($featuredImage === null && $this->featuredImage !== null) {
            $this->featuredImage->setTrip(null);
        }

        // set the owning side of the relation if necessary
        if ($featuredImage !== null && $featuredImage->getTrip() !== $this) {
            $featuredImage->setTrip($this);
        }

        $this->featuredImage = $featuredImage;

        return $this;
    }

    
}
