<?php

namespace App\Classes;

use App\Entity\Ticket;
use Doctrine\ORM\EntityManager;

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

    public array $customerInf;
    public array $flightInf;

    public function parseResults($results, Search $search)
    {
        $flightResults = false;
        $tools = new Tool();

        if ($results) {
            foreach ($results as $result) {
                $flightResult = new FlightResult();
                $flightResult->setFromWhere($result['VON']);
                $flightResult->setToWhere($result['NACH']);
                $flightResult->setDepartingDate($result['Datum']);
                $flightResult->setArrivingDate($result['Ankunftsdatum']);
                $flightResult->setDepartureTime($result['ABFLUG']);
                $flightResult->setArrivalTime($result['ANKUNFT']);
                $flightResult->setFlightTime($result['Flugzeit']);
                $flightResult->setPrice($result['Preis_ERW_RoundtTrip']);
                /*$flightResult->setPriceADT($result['Preis_ERW']);
                $flightResult->setPriceKid($result['Preis_KIND']);
                $flightResult->setPriceInf($result['Preis_BABY']);*/
                $flightResult->setPriceADT($result['Preis_ERW']);
                $flightResult->setPriceKid(isset($result['Preis_KIND'])?($result['Preis_KIND']):0);
                $flightResult->setPriceInf(isset($result['Preis_BABY'])?($result['Preis_BABY']):0);
                $flightResult->setPriceADTRT($result['Preis_ERW_RoundtTrip']);
                $flightResult->setPriceKidRT(isset($result['Preis_KIND_RoundtTrip'])?($result['Preis_KIND_RoundtTrip']):0);
                $flightResult->setPriceInfRT(isset($result['Preis_BABY_RoundtTrip'])?($result['Preis_BABY_RoundtTrip']):0);
                $flightResult->setTax($result['TAX']);
                $flightResult->setTotalPrice(($flightResult->getPriceADT() * $search->whoADT) + ($flightResult->getPriceKid() * $search->whoKid) + ($flightResult->getPriceInf() * $search->whoInf));
                $flightResult->setCurrency($result['Waehrung']);
                $flightResult->setTotalPriceDisp($tools->formatPrice($flightResult->getTotalPrice(), $flightResult->getCurrency(), 1));
                $flightResult->setPriceClass($result['Tarif']);
                $flightResult->setFlightNumber($result['FLUGNUMMER']);
                $flightResult->setFlightID($result['FlugID']);
                $flightResult->setAirline($result['AIRLINE']);
                $flightResult->setAirlineCode($tools->createSlug($flightResult->getAirline()));
                $flightResult->setCarrier($result['CARRIER']);
                $flightResult->setFlightType($result['FLUGZEUGTYP']);
                $flightResult->setFreeBag($result['FREIGEPAECK']);
                $flightResult->setFreeBagSize($result['MAX_ANZAHL_GEPAECKSTUECKE']);
                $flightResult->setAdt($search->whoADT);
                $flightResult->setKid($search->whoKid);
                $flightResult->setInf($search->whoInf);
                $flightResult->setHasTwoWay($search->returnDate!=''?1:0);

                //GMT yi tarihlere eklemiş görünüyor, GMT ye göre işlem yapmaya gerek yok, yolladğı tarih ve saat direkt basılabilir
                $dateDep3 = \DateTime::createFromFormat('d.m.Y H:i', $flightResult->getDepartingDate().' '.$flightResult->getDepartureTime());
                $flightResult->departingDateDisp = $dateDep3->format('D j, Y, g:i a');

                $dateArr3 = \DateTime::createFromFormat('d.m.Y H:i', $flightResult->getArrivingDate().' '.$flightResult->getArrivalTime());
                $flightResult->arrivingDateDisp = $dateArr3->format('D j, Y, g:i a');

                //uçuş süresi için uçuş tarihleri arasındaki farkı bul dersek GMT den dolayı hatalı sonuç verir, bu yüzden kendi gönderdiğini alıyoruz
                $flightResult->flightTimeDisp = $tools->parseHourMinute($flightResult->getFlightTime());
                $flightResult->flightTimeShort = $tools->parseHourMinute($flightResult->getFlightTime(), 1);

                $flightResults[] = $flightResult;
            }
        }

        //$results = $result;

        return $flightResults;
    }

    public function parsePnrResult($pnrResponse)
    {
        if (isset($pnrResponse['REISETEILNEHMER']['VORNAME']))
        {
            $customers[0]   = $pnrResponse['REISETEILNEHMER'];
        }
        else {
            $customers   = $pnrResponse['REISETEILNEHMER'];
        }

        $flightInf_      = $pnrResponse['LEISTUNGEN'];
        $priceInf       = $pnrResponse['BUCHUNGSDATEN'];

        $totalPrice = 0;

        $cntCus = count($customers);
        for ($iCm=0; $iCm<$cntCus; $iCm++)
        {
            $customerArr[$iCm] = new \stdClass();
            $customerArr[$iCm]->name = $customers[$iCm]['VORNAME'];
            $customerArr[$iCm]->lastName = $customers[$iCm]['NACHNAME'];
            $customerArr[$iCm]->gender = $customers[$iCm]['ANREDE'];
            $customerArr[$iCm]->price = $customers[$iCm]['FLUGPREIS'];
            $customerArr[$iCm]->tax = $customers[$iCm]['TAX'];
            $customerArr[$iCm]->totalPrice = $customers[$iCm]['SUMME'];
            $customerArr[$iCm]->currency = $priceInf['WAEHRUNG'];
            $totalPrice += $customerArr[$iCm]->totalPrice;//tam toplamı alıyoruz ve kolay erişim için yine buraya atıyoruz
            $customerArr[0]->grandTotalPrice = $totalPrice;
            $this->customerInf = $customerArr;
        }

        if (isset($flightInf_['VON']))
        {
            $flightInf[0] = $flightInf_;
        }
        else {
            $flightInf = $flightInf_;
        }

        $cntFi = count($flightInf);
        for ($iFi=0; $iFi<$cntFi; $iFi++)
        {
            $flighInfArr[$iFi] = new \stdClass();
            $flighInfArr[$iFi]->fromWhere = $flightInf[$iFi]['VON'];
            $flighInfArr[$iFi]->toWhere = $flightInf[$iFi]['NACH'];
            $flighInfArr[$iFi]->fromCity = $flightInf[$iFi]['VON_ORT'];
            $flighInfArr[$iFi]->toCity = $flightInf[$iFi]['NACH_ORT'];
            $flighInfArr[$iFi]->airline = $flightInf[$iFi]['AIRLINE'];
            $flighInfArr[$iFi]->flightNumber = $flightInf[$iFi]['NUMMER'];
            $flighInfArr[$iFi]->dateDeparture = $flightInf[$iFi]['DATUM'].' '.$flightInf[$iFi]['ABFLUG'];
            $flighInfArr[$iFi]->dateArrive = $flightInf[$iFi]['DATUM'].' '.$flightInf[$iFi]['ANKUNFT'];
            $this->flightInf = $flighInfArr;
        }

        if ($cntFi>1)
        {
            $this->hasReturn = true;
        }

        return $this;
    }

    public function sale_success(EntityManager $entityManager, Ticket $ticket, $ticketId, $pnr, $supplierResponsePrice, $ticketCustomers): ResponseZy
    {
        $responseZy = new ResponseZy();

        try {
            $ticket->setPnrNo($pnr);
            $ticket->setSupplierResponsePrice($supplierResponsePrice);
            $ticket->setStatus(1);

            //müşterileri DB ye yazma. Yetişkin 1 i yazdık (fakat yazmamış olabilir)
            foreach ($ticketCustomers as $ticketCustomer)
            {
                if ($ticketCustomer->customerId)
                {
                    continue;
                }
                $ticketCustomerSave = new TicketCustomer();
                $ticketCustomerSave->save_customer($entityManager, $ticket, $ticketCustomer);
            }

            //pnr ı müşteriye de yazıyoruz
            $ticketCustomerUpdate = $entityManager->getRepository(\App\Entity\TicketCustomer::class)->find($ticketCustomers[0]->customerId);
            //var_dump($ticketCustomerUpdate);
            if ($ticketCustomerUpdate)
            {
                $ticketCustomerUpdate->setPnrNo($pnr);
            }
        }
        catch (\Exception $exception) {
            $responseZy->status = false;
            $responseZy->message = $exception->getMessage();
        }

        return $responseZy;
    }
}