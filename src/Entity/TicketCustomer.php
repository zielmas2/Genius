<?php

namespace App\Entity;

use App\Repository\TicketCustomerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketCustomerRepository::class)]
class TicketCustomer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ticket_customer')]
    private ?Ticket $ticket_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $ticket_customer = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $class = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $customer_type = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $identity_no = null;

    #[ORM\Column(length: 64)]
    private ?string $name = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $surname = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $gender = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_of_birth = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $baggage = null;

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[ORM\Column(nullable: true)]
    private ?float $tax = null;

    #[ORM\Column(nullable: true)]
    private ?float $price_commission = null;

    #[ORM\Column(nullable: true)]
    private ?float $price_total = null;

    #[ORM\Column(length: 96, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 19, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 9, nullable: true)]
    private ?string $seat_no = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $pnr_no = null;

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

    #[ORM\Column(length: 1)]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTicketId(): ?Ticket
    {
        return $this->ticket_id;
    }

    public function setTicketId(?Ticket $ticket_id): self
    {
        $this->ticket_id = $ticket_id;

        return $this;
    }

    public function getTicketCustomer(): ?int
    {
        return $this->ticket_customer;
    }

    public function setTicketCustomer(?int $ticket_customer): self
    {
        $this->ticket_customer = $ticket_customer;

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(?string $class): self
    {
        $this->class = $class;

        return $this;
    }

    public function getCustomerType(): ?int
    {
        return $this->customer_type;
    }

    public function setCustomerType(?int $customer_type): self
    {
        $this->customer_type = $customer_type;

        return $this;
    }

    public function getIdentityNo(): ?string
    {
        return $this->identity_no;
    }

    public function setIdentityNo(?string $identity_no): self
    {
        $this->identity_no = $identity_no;

        return $this;
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

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getGender(): ?int
    {
        return $this->gender;
    }

    public function setGender(?int $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->date_of_birth;
    }

    public function setDateOfBirth(?\DateTimeInterface $date_of_birth): self
    {
        $this->date_of_birth = $date_of_birth;

        return $this;
    }

    public function getBaggage(): ?string
    {
        return $this->baggage;
    }

    public function setBaggage(?string $baggage): self
    {
        $this->baggage = $baggage;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getTax(): ?float
    {
        return $this->tax;
    }

    public function setTax(?float $tax): self
    {
        $this->tax = $tax;

        return $this;
    }

    public function getPriceCommission(): ?float
    {
        return $this->price_commission;
    }

    public function setPriceCommission(?float $price_commission): self
    {
        $this->price_commission = $price_commission;

        return $this;
    }

    public function getPriceTotal(): ?float
    {
        return $this->price_total;
    }

    public function setPriceTotal(?float $price_total): self
    {
        $this->price_total = $price_total;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getSeatNo(): ?string
    {
        return $this->seat_no;
    }

    public function setSeatNo(?string $seat_no): self
    {
        $this->seat_no = $seat_no;

        return $this;
    }

    public function getPnrNo(): ?string
    {
        return $this->pnr_no;
    }

    public function setPnrNo(?string $pnr_no): self
    {
        $this->pnr_no = $pnr_no;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
