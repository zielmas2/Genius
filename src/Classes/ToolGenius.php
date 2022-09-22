<?php

namespace App\Classes;

class ToolGenius
{
    public string $name;
    public string $lastName;
    public string $gender;
    public float $price;
    public float $tax;
    public float $totalPrice;
    public string $currency;
    public string $fromWhere;
    public string $toWhere;
    public string $fromCity;
    public string $toCity;
    public string $airline;
    public string $flightNumber;
    public string $dateDeparture;
    public string $dateArrive;
    public bool $hasReturn = false;

    public function parsePnrResult($pnrResponse)
    {
        if (isset($pnrResponse['REISETEILNEHMER']['VORNAME']))
        {
            $customers[0]   = $pnrResponse['REISETEILNEHMER'];
        }
        else {
            $customers   = $pnrResponse['REISETEILNEHMER'];
        }

        $flightInf      = $pnrResponse['LEISTUNGEN'];
        $priceInf       = $pnrResponse['BUCHUNGSDATEN'];

        if (isset($customers[0]['VORNAME']))
        {
            $this->name = $customers[0]['VORNAME'];
            $this->lastName = $customers[0]['NACHNAME'];
            $this->gender = $customers[0]['ANREDE'];
            $this->price = $customers[0]['FLUGPREIS'];
            $this->tax = $customers[0]['TAX'];
            $this->totalPrice = $customers[0]['SUMME'];

            $this->currency = $priceInf['WAEHRUNG'];
        }

        if (isset($flightInf['VON']))
        {
            $this->fromWhere = $flightInf['VON'];
            $this->toWhere = $flightInf['NACH'];
            $this->fromCity = $flightInf['VON_ORT'];
            $this->toCity = $flightInf['NACH_ORT'];
            $this->airline = $flightInf['AIRLINE'];
            $this->flightNumber = $flightInf['NUMMER'];
            $this->dateDeparture = $flightInf['DATUM'].' '.$flightInf['ABFLUG'];
            $this->dateArrive = $flightInf['DATUM'].' '.$flightInf['ANKUNFT'];
        }

        return $this;
    }
}