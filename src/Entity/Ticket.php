<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?SearchTicket $search_ticket_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $airline = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $airline_code = null;

    #[ORM\Column(length: 16)]
    private ?string $flight_number = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $aircraft_type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $baggage = null;

    #[ORM\Column(length: 96, nullable: true)]
    private ?string $flight_id = null;

    #[ORM\Column(length: 96, nullable: true)]
    private ?string $flight_time = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $carrier = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $departure_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $arriving_date = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $direction = null;

    #[ORM\Column]
    private ?float $adult_price = null;

    #[ORM\Column(nullable: true)]
    private ?float $price_kid = null;

    #[ORM\Column(nullable: true)]
    private ?float $price_inf = null;

    #[ORM\Column(nullable: true)]
    private ?float $tax = null;

    #[ORM\Column(length: 3)]
    private ?string $currency = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $pnr_no = null;

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

    #[ORM\OneToMany(mappedBy: 'ticket_id', targetEntity: TicketCustomer::class)]
    private Collection $ticket_customer;

    #[ORM\Column(nullable: true)]
    private ?float $supplier_response_price = null;

    public function __construct()
    {
        $this->ticket_customer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSearchTicketId(): ?SearchTicket
    {
        return $this->search_ticket_id;
    }

    public function setSearchTicketId(?SearchTicket $search_ticket_id): self
    {
        $this->search_ticket_id = $search_ticket_id;

        return $this;
    }

    public function getAirline(): ?string
    {
        return $this->airline;
    }

    public function setAirline(?string $airline): self
    {
        $this->airline = $airline;

        return $this;
    }

    public function getAirlineCode(): ?string
    {
        return $this->airline_code;
    }

    public function setAirlineCode(string $airline_code): self
    {
        $this->airline_code = $airline_code;

        return $this;
    }

    public function getFlightNumber(): ?string
    {
        return $this->flight_number;
    }

    public function setFlightNumber(string $flight_number): self
    {
        $this->flight_number = $flight_number;

        return $this;
    }

    public function getAircraftType(): ?string
    {
        return $this->aircraft_type;
    }

    public function setAircraftType(?string $aircraft_type): self
    {
        $this->aircraft_type = $aircraft_type;

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

    public function getFlightId(): ?string
    {
        return $this->flight_id;
    }

    public function setFlightId(?string $flight_id): self
    {
        $this->flight_id = $flight_id;

        return $this;
    }

    public function getFlightTime(): ?string
    {
        return $this->flight_time;
    }

    public function setFlightTime(?string $flight_time): self
    {
        $this->flight_time = $flight_time;

        return $this;
    }

    public function getCarrier(): ?string
    {
        return $this->carrier;
    }

    public function setCarrier(?string $carrier): self
    {
        $this->carrier = $carrier;

        return $this;
    }

    public function getDepartureDate(): ?\DateTimeInterface
    {
        return $this->departure_date;
    }

    public function setDepartureDate(\DateTimeInterface $departure_date): self
    {
        $this->departure_date = $departure_date;

        return $this;
    }

    public function getArrivingDate(): ?\DateTimeInterface
    {
        return $this->arriving_date;
    }

    public function setArrivingDate(\DateTimeInterface $arriving_date): self
    {
        $this->arriving_date = $arriving_date;

        return $this;
    }

    public function getDirection(): ?int
    {
        return $this->direction;
    }

    public function setDirection(int $direction): self
    {
        $this->direction = $direction;

        return $this;
    }

    public function getAdultPrice(): ?float
    {
        return $this->adult_price;
    }

    public function setAdultPrice(float $adult_price): self
    {
        $this->adult_price = $adult_price;

        return $this;
    }

    public function getPriceKid(): ?float
    {
        return $this->price_kid;
    }

    public function setPriceKid(?float $price_kid): self
    {
        $this->price_kid = $price_kid;

        return $this;
    }

    public function getPriceInf(): ?float
    {
        return $this->price_inf;
    }

    public function setPriceInf(?float $price_inf): self
    {
        $this->price_inf = $price_inf;

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

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

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

    /**
     * @return Collection<int, TicketCustomer>
     */
    public function getTicketCustomer(): Collection
    {
        return $this->ticket_customer;
    }

    public function addTicketCustomer(TicketCustomer $ticketCustomer): self
    {
        if (!$this->ticket_customer->contains($ticketCustomer)) {
            $this->ticket_customer->add($ticketCustomer);
            $ticketCustomer->setTicketId($this);
        }

        return $this;
    }

    public function removeTicketCustomer(TicketCustomer $ticketCustomer): self
    {
        if ($this->ticket_customer->removeElement($ticketCustomer)) {
            // set the owning side to null (unless already changed)
            if ($ticketCustomer->getTicketId() === $this) {
                $ticketCustomer->setTicketId(null);
            }
        }

        return $this;
    }

    public function getSupplierResponsePrice(): ?float
    {
        return $this->supplier_response_price;
    }

    public function setSupplierResponsePrice(?float $supplier_response_price): self
    {
        $this->supplier_response_price = $supplier_response_price;

        return $this;
    }
}
