<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApiTokenRepository")
 */
class ApiToken
{
    /**
     * ApiToken constructor.
     * @param User $user
     * @throws \Exception
     */
    public function __construct(User $user)
    {
        $this->token = bin2hex(random_bytes(60));
        $this->user = $user;
        $this->expiresAt = new \DateTime('+1 hour');
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expiresAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="apiTokens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }
    /*
    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }
    */
    /**
     * @return \DateTimeInterface|null
     */
    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }
    /*
    public function setExpiresAt(\DateTimeInterface $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }
    */
    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }
    /*
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
    */

    /**
     * @return \DateTime|null
     * @throws \Exception
     */
    public function renewExpiresAt(): ?\DateTime
    {
        $expiresAt = new \DateTime('+1 hour');

        return $expiresAt;
    }
    public function isExpired(): bool
    {
        return $this->getExpiresAt() <= new \DateTime();
    }
}
