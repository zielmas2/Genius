<?php

namespace App\Entity;

use App\Repository\SearchTicketRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SearchTicketRepository::class)]
class SearchTicket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5)]
    private ?string $from_where = null;

    #[ORM\Column(length: 5)]
    private ?string $to_where = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $departing_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $return_departing_date = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $adult = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $kid = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $infant = null;

    #[ORM\Column(nullable: true)]
    private ?float $price_total = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $ip_address = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?int $created_by = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(nullable: true)]
    private ?int $updated_by = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdult(): ?int
    {
        return $this->adult;
    }

    public function getFromWhere(): ?string
    {
        return $this->from_where;
    }

    public function setFromWhere(string $from_where): self
    {
        $this->from_where = $from_where;

        return $this;
    }

    public function getToWhere(): ?string
    {
        return $this->to_where;
    }

    public function setToWhere(string $to_where): self
    {
        $this->to_where = $to_where;

        return $this;
    }

    public function getDepartingDate(): ?\DateTimeInterface
    {
        return $this->departing_date;
    }

    public function setDepartingDate(\DateTimeInterface $departing_date): self
    {
        $this->departing_date = $departing_date;

        return $this;
    }

    public function getReturnDepartingDate(): ?\DateTimeInterface
    {
        return $this->return_departing_date;
    }

    public function setReturnDepartingDate(?\DateTimeInterface $return_departing_date): self
    {
        $this->return_departing_date = $return_departing_date;

        return $this;
    }

    public function setAdult(int $adult): self
    {
        $this->adult = $adult;

        return $this;
    }

    public function getKid(): ?int
    {
        return $this->kid;
    }

    public function setKid(?int $kid): self
    {
        $this->kid = $kid;

        return $this;
    }

    public function getInfant(): ?int
    {
        return $this->infant;
    }

    public function setInfant(?int $infant): self
    {
        $this->infant = $infant;

        return $this;
    }

    public function getPriceTotal(): ?float
    {
        return $this->price_total;
    }

    public function setPriceTotal(float $price_total): self
    {
        $this->price_total = $price_total;

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ip_address;
    }

    public function setIpAddress(?string $ip_address): self
    {
        $this->ip_address = $ip_address;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->created_by;
    }

    public function setCreatedBy(?int $created_by): self
    {
        $this->created_by = $created_by;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getUpdatedBy(): ?int
    {
        return $this->updated_by;
    }

    public function setUpdatedBy(?int $updated_by): self
    {
        $this->updated_by = $updated_by;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
