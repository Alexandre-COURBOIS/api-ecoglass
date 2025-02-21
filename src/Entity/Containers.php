<?php

namespace App\Entity;

use App\Repository\ContainersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContainersRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Containers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $containerId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $street;

    /**
     * @ORM\Column(type="float")
     */
    private $longitude;

    /**
     * @ORM\Column(type="float")
     */
    private $latitude;

    /**
     * @ORM\Column(type="geography", options={"geometry_type"="POINT"})
     */
    private $coordinates;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function prePersist()
    {
        if (empty($this->getCreatedAt())) {
            $this->setCreatedAt(new \DateTime());
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContainerId(): ?int
    {
        return $this->containerId;
    }

    public function setContainerId(int $containerId): self
    {
        $this->containerId = $containerId;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity($city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postalCode;
    }

    public function setPostalCode(int $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet($street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCoordinates()
    {
        $cooSRID = $this->coordinates;
        $coo = substr($cooSRID, 16, -1); // remove SRID
        $gps = str_replace(' ', ', ',$coo); //replace space with coma
        return $gps;
    }

    public function setCoordinates($coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }
}
