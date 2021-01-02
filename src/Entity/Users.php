<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Cet email est déjà utilisé.",
 *     groups={"Register"},
 * )
 *
 * @UniqueEntity(
 *     fields={"pseudo"},
 *     message="Ce pseudo est déjà utilisé.",
 *     groups={"Register"},
 * )
 *
 */
class Users implements UserInterface
{
    /**
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     *
     * @ORM\Column(type="integer")
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(
     *     message="Merci de renseigner votre nom.",
     *     groups={"Register"}
     *     )
     *
     * @Assert\Length(
     *     min="2",
     *     minMessage="Merci de renseigner un nom correct",
     *     groups={"Register"}
     *     )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(
     *     message="Merci de renseigner votre prenom.",
     *     groups={"Register"}
     *     )
     *
     * @Assert\Length(
     *     min="2",
     *     minMessage="Merci de renseigner un prenom correct",
     *     groups={"Register"}
     *     )
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(
     *     message="Merci de renseigner votre pseudo.",
     *     groups={"Register"}
     *     )
     *
     * @Assert\Length(
     *     min="2",
     *     minMessage="Merci de renseigner un pseudo correct",
     *     groups={"Register"}
     *     )
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(
     *     message="Merci de renseigner votre email.",
     *     groups={"Register"}
     *     )
     *
     * @Assert\Email(
     *     message="Veuillez renseigner un mail valide.",
     *     groups={"Register"}
     *     )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Length(
     *     min="5",
     *     minMessage="Merci de renseigner une adresse correct",
     *     groups={"Register"}
     *     )
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(
     *     message="Merci de renseigner votre ville.",
     *     groups={"Register"}
     *     )
     *
     * @Assert\Length(
     *     min="2",
     *     minMessage="Merci de renseigner une ville existante.",
     *     groups={"Register"}
     *     )
     *
     */
    private $city;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank(
     *     message="Merci de renseigner votre code postal.",
     *     groups={"Register"}
     *     )
     *
     * @Assert\Length(
     *     min="2",
     *     minMessage="Merci de renseigner un code postale valide.",
     *     groups={"Register"}
     *     )
     *
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resetToken;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @Assert\NotBlank(
     *     message="Merci de renseigner votre mot de passe.",
     *     groups={"PasswordUpdate"},
     *     )
     *
     * @Assert\Length(
     *     min="8",
     *     minMessage="Veuillez renseigner un mot de passe d'au moins 8 caractères.",
     *     groups={"PasswordUpdate"},
     *     )
     *
     * @Assert\Regex(
     *     pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/",
     *     message="Votre mot de passe doit contenir au moins caractère spécial, une majuscule ainsi qu'un chiffre.",
     *     groups={"PasswordUpdate"},
     * )
     *
     * @Assert\NotBlank(
     *     message="Merci de renseigner votre mot de passe.",
     *     groups={"Register"},
     *     )
     *
     * @Assert\Length(
     *     min="8",
     *     minMessage="Veuillez renseigner un mot de passe d'au moins 8 caractères.",
     *     groups={"Register"},
     *     )
     *
     * @Assert\Regex(
     *     pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/",
     *     message="Votre mot de passe doit contenir au moins caractère spécial, une majuscule ainsi qu'un chiffre.",
     *     groups={"Register"},
     * )
     *
     * @Assert\NotBlank(
     *     message="Merci de renseigner votre mot de passe.",
     *     )
     *
     * @Assert\Length(
     *     min="8",
     *     minMessage="Veuillez renseigner un mot de passe d'au moins 8 caractères.",
     *     )
     *
     * @Assert\Regex(
     *     pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/",
     *     message="Votre mot de passe doit contenir au moins caractère spécial, une majuscule ainsi qu'un chiffre.",
     * )
     *
     */
    private $password;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $idFacebook;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function prePersist()
    {
        if (empty($this->getRoles())) {
            $this->setRoles(['ROLE_USER']);
        }

        if (empty($this->getCreatedAt())) {
            $this->setCreatedAt(new \DateTime());
        }

        if (empty($this->getToken())) {
            $this->setToken(rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '='));
        }

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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
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

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }


    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getIdFacebook(): ?string
    {
        return $this->idFacebook;
    }

    public function setIdFacebook(string $idFacebook): self
    {
        $this->idFacebook = $idFacebook;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getRoles()
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
       return $this->email;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
