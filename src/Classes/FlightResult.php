<?php

namespace App\Classes;

class FlightResult
{
    public string $fromWhere        = '';
    public string $toWhere;
    public string $departingDate;
    public string $arrivingDate;
    public string $departureTime    = '';
    public string $arrivalTime      = '';
    public bool $hasTwoWay              = false;
    public bool $hasTransfer            = false;
    public string $cityTakeOff      = '';
    public string $cityLanding      = '';
    public float $price;
    public float $priceADT;
    public float $priceKid          = 0;
    public float $priceInf          = 0;
    public float $priceADTRT;
    public float $priceKidRT        = 0;
    public float $priceInfRT        = 0;
    public float $tax               = 0;
    public float $totalPrice;
    public string $totalPriceDisp;
    public string $priceClass;
    public string $currency;
    public string $airline;
    public string $airlineCode;
    public string $carrier          = '';
    public string $flightNumber     = '';
    public string $flightID         = '';
    public string $flightTime       = '';
    public string $flightType       = '';
    public string $freeBag          = '';
    public string $freeBagSize      = '';
    public int $adt;
    public int $kid     = 0;
    public int $inf     = 0;

    public string $departingDateDisp    = '';
    public string $arrivingDateDisp     = '';
    public string $flightTimeDisp       = '';
    public string $flightTimeShort      = '';

    /**
     * @return string
     */
    public function getFromWhere(): string
    {
        return $this->fromWhere;
    }

    /**
     * @param string $fromWhere
     */
    public function setFromWhere(string $fromWhere): void
    {
        $this->fromWhere = $fromWhere;
    }

    /**
     * @return string
     */
    public function getToWhere(): string
    {
        return $this->toWhere;
    }

    /**
     * @param string $toWhere
     */
    public function setToWhere(string $toWhere): void
    {
        $this->toWhere = $toWhere;
    }

    /**
     * @return string
     */
    public function getDepartingDate(): string
    {
        return $this->departingDate;
    }

    /**
     * @param string $departingDate
     */
    public function setDepartingDate(string $departingDate): void
    {
        $this->departingDate = $departingDate;
    }

    /**
     * @return string
     */
    public function getArrivingDate(): string
    {
        return $this->arrivingDate;
    }

    /**
     * @param string $arrivingDate
     */
    public function setArrivingDate(string $arrivingDate): void
    {
        $this->arrivingDate = $arrivingDate;
    }

    /**
     * @return bool
     */
    public function isHasTwoWay(): bool
    {
        return $this->hasTwoWay;
    }

    /**
     * @param bool $hasTwoWay
     */
    public function setHasTwoWay(bool $hasTwoWay): void
    {
        $this->hasTwoWay = $hasTwoWay;
    }

    /**
     * @return bool
     */
    public function isHasTransfer(): bool
    {
        return $this->hasTransfer;
    }

    /**
     * @param bool $hasTransfer
     */
    public function setHasTransfer(bool $hasTransfer): void
    {
        $this->hasTransfer = $hasTransfer;
    }

    /**
     * @return string
     */
    public function getCityTakeOff(): string
    {
        return $this->cityTakeOff;
    }

    /**
     * @param string $cityTakeOff
     */
    public function setCityTakeOff(string $cityTakeOff): void
    {
        $this->cityTakeOff = $cityTakeOff;
    }

    /**
     * @return string
     */
    public function getCityLanding(): string
    {
        return $this->cityLanding;
    }

    /**
     * @param string $cityLanding
     */
    public function setCityLanding(string $cityLanding): void
    {
        $this->cityLanding = $cityLanding;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getDepartureTime(): string
    {
        return $this->departureTime;
    }

    /**
     * @param string $departureTime
     */
    public function setDepartureTime(string $departureTime): void
    {
        $this->departureTime = $departureTime;
    }

    /**
     * @return string
     */
    public function getArrivalTime(): string
    {
        return $this->arrivalTime;
    }

    /**
     * @param string $arrivalTime
     */
    public function setArrivalTime(string $arrivalTime): void
    {
        $this->arrivalTime = $arrivalTime;
    }

    /**
     * @return float
     */
    public function getPriceADT(): float
    {
        return $this->priceADT;
    }

    /**
     * @param float $priceADT
     */
    public function setPriceADT(float $priceADT): void
    {
        $this->priceADT = $priceADT;
    }

    /**
     * @return float
     */
    public function getPriceKid(): float
    {
        return $this->priceKid;
    }

    /**
     * @param float $priceKid
     */
    public function setPriceKid(float $priceKid): void
    {
        $this->priceKid = $priceKid;
    }

    /**
     * @return float
     */
    public function getPriceInf(): float
    {
        return $this->priceInf;
    }

    /**
     * @param float $priceInf
     */
    public function setPriceInf(float $priceInf): void
    {
        $this->priceInf = $priceInf;
    }

    /**
     * @return float
     */
    public function getPriceADTRT(): float
    {
        return $this->priceADTRT;
    }

    /**
     * @param float $priceADTRT
     */
    public function setPriceADTRT(float $priceADTRT): void
    {
        $this->priceADTRT = $priceADTRT;
    }

    /**
     * @return float
     */
    public function getPriceKidRT(): float
    {
        return $this->priceKidRT;
    }

    /**
     * @param float $priceKidRT
     */
    public function setPriceKidRT(float $priceKidRT): void
    {
        $this->priceKidRT = $priceKidRT;
    }

    /**
     * @return float
     */
    public function getPriceInfRT(): float
    {
        return $this->priceInfRT;
    }

    /**
     * @param float $priceInfRT
     */
    public function setPriceInfRT(float $priceInfRT): void
    {
        $this->priceInfRT = $priceInfRT;
    }

    /**
     * @return float
     */
    public function getTax(): float
    {
        return $this->tax;
    }

    /**
     * @param float $tax
     */
    public function setTax(float $tax): void
    {
        $this->tax = $tax;
    }

    /**
     * @return float
     */
    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    /**
     * @param float $totalPrice
     */
    public function setTotalPrice(float $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return string
     */
    public function getTotalPriceDisp(): string
    {
        return $this->totalPriceDisp;
    }

    /**
     * @param string $totalPriceDisp
     */
    public function setTotalPriceDisp(string $totalPriceDisp): void
    {
        $this->totalPriceDisp = $totalPriceDisp;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getAirline(): string
    {
        return $this->airline;
    }

    /**
     * @param string $airline
     */
    public function setAirline(string $airline): void
    {
        $this->airline = $airline;
    }

    /**
     * @return string
     */
    public function getCarrier(): string
    {
        return $this->carrier;
    }

    /**
     * @param string $carrier
     */
    public function setCarrier(string $carrier): void
    {
        $this->carrier = $carrier;
    }

    /**
     * @return string
     */
    public function getFlightNumber(): string
    {
        return $this->flightNumber;
    }

    /**
     * @param string $flightNumber
     */
    public function setFlightNumber(string $flightNumber): void
    {
        $this->flightNumber = $flightNumber;
    }

    /**
     * @return string
     */
    public function getFlightID(): string
    {
        return $this->flightID;
    }

    /**
     * @param string $flightID
     */
    public function setFlightID(string $flightID): void
    {
        $this->flightID = $flightID;
    }

    /**
     * @return string
     */
    public function getFlightTime(): string
    {
        return $this->flightTime;
    }

    /**
     * @param string $flightTime
     */
    public function setFlightTime(string $flightTime): void
    {
        $this->flightTime = $flightTime;
    }

    /**
     * @return string
     */
    public function getFlightType(): string
    {
        return $this->flightType;
    }

    /**
     * @param string $flightType
     */
    public function setFlightType(string $flightType): void
    {
        $this->flightType = $flightType;
    }

    /**
     * @return string
     */
    public function getFreeBag(): string
    {
        return $this->freeBag;
    }

    /**
     * @param string $freeBag
     */
    public function setFreeBag(string $freeBag): void
    {
        $this->freeBag = $freeBag;
    }

    /**
     * @return string
     */
    public function getFreeBagSize(): string
    {
        return $this->freeBagSize;
    }

    /**
     * @param string $freeBagSize
     */
    public function setFreeBagSize(string $freeBagSize): void
    {
        $this->freeBagSize = $freeBagSize;
    }

    /**
     * @return string
     */
    public function getPriceClass(): string
    {
        return $this->priceClass;
    }

    /**
     * @param string $priceClass
     */
    public function setPriceClass(string $priceClass): void
    {
        $this->priceClass = $priceClass;
    }

    /**
     * @return string
     */
    public function getAirlineCode(): string
    {
        return $this->airlineCode;
    }

    /**
     * @param string $airlineCode
     */
    public function setAirlineCode(string $airlineCode): void
    {
        $this->airlineCode = $airlineCode;
    }

    /**
     * @return int
     */
    public function getAdt(): int
    {
        return $this->adt;
    }

    /**
     * @param int $adt
     */
    public function setAdt(int $adt): void
    {
        $this->adt = $adt;
    }

    /**
     * @return int
     */
    public function getKid(): int
    {
        return $this->kid;
    }

    /**
     * @param int $kid
     */
    public function setKid(int $kid): void
    {
        $this->kid = $kid;
    }

    /**
     * @return int
     */
    public function getInf(): int
    {
        return $this->inf;
    }

    /**
     * @param int $inf
     */
    public function setInf(int $inf): void
    {
        $this->inf = $inf;
    }


}