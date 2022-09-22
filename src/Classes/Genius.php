<?php

namespace App\Classes;

use App\Entity\SearchTicket;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class Genius
{
    private string $address = 'http://80.81.250.53/GeniusServerTest/GeniusServer.asmx?wsdl';
    private string $agenturID;// = $_ENV["GENIUS_AID"];//getenv('GENIUS_AID');
    private string $key;// = $_ENV["GENIUS_KEY"];//getenv('GENIUS_KEY');
    public bool $debug = false;

    function __construct() {

    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getAgenturID()
    {
        return $this->agenturID;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setApiSetting(ParameterBagInterface $parameter) {//github a gönderilince şifreler gitmesin diye .env de tutuyoruz
        $this->agenturID = $parameter->get('genius_aid');
        $this->key = $parameter->get('genius_key');
    }

    public function getFlights(Search $search)
    {
        try {
            $client = new \nusoap_client($this->address, 'wsdl');
            $client->soap_defencoding = 'UTF-8';
            $client->decode_utf8 = false;

            $geniusData = array(
                'parAgenturID'      => $this->agenturID,
                'parKey'            => $this->key,
                'parHinflugDatum'   => $search->departDate,
                'parRueckflugdatum' => $search->returnDate,
                'parVon'            => $search->fromWhere,
                'parNach'           => $search->toWhere,
                'parCarrier'        => '',
                'parAnzahlERW'      => $search->whoADT,
                'parAnzahlKind'     => $search->whoKid,
                'parAnzahlBaby'     => $search->whoInf,
            );
            //var_dump($geniusData);

            $error  = $client->getError();

            if ($error and $this->debug) {
                echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
            }

            $result = $client->call('GV', $geniusData);
            //echo '<pre>---:' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
            //var_dump($result);
            //print_r($result);
            //var_dump($result['GVResult']);
            if (empty($result['GVResult']['diffgram']['NewDataSet']['GV_Hinflug']))
            {
                throw new \Exception('No Flight found!');
            }
            if (isset($result['GVResult']['diffgram']['NewDataSet']['GV_Hinflug']['VON'])) {
                $results[0] = $result['GVResult']['diffgram']['NewDataSet']['GV_Hinflug'];
            }
            else {
                $results = $result['GVResult']['diffgram']['NewDataSet']['GV_Hinflug'];
            }
            //var_dump($results);
            //var_dump($results[0]['ZUSCHLAEGE']);


            if ($client->fault) {
                if ($this->debug) {
                    echo "<h2>Fault</h2><pre>"; print_r($result); echo "</pre>";
                }
                throw new \Exception(serialize($client->fault));
            }
            else {
                $error = $client->getError();
                if ($error) {
                    if ($this->debug) {
                        echo "<h2>Error</h2><pre>" . $error . "</pre>";
                    }
                    throw new \Exception(serialize($error));
                }
                else {
                    if ($this->debug) {
                        echo "<h2>Main</h2>"; print_r($result);
                    }
                }
            }

            return $this->parseResults($results, $search);
        }
        catch (\Exception $exception) {
            //var_dump($exception->getMessage());
            return false;
        }
    }

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
                $flightResult->setPriceADT($result['Preis_ERW']);
                $flightResult->setPriceKid($result['Preis_KIND']);
                $flightResult->setPriceInf($result['Preis_BABY']);
                $flightResult->setTax($result['TAX']);
                $flightResult->setCurrency($result['Waehrung']);
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

                $flightResults[] = $flightResult;
            }
        }

        //$results = $result;

        return $flightResults;
    }

    public function searchFromHttpRequest(Request $request, EntityManager $entityManager, Session $session, $parameterBag):array {
        $responseSearch = array(
            'status' => true
        );

        try {
            $tools = new Tool();
            $search = new Search();

            $adult = $request->get('whoADT');
            $kid = $request->get('whoKid');
            $inf = $request->get('whoInf');

            $search->fromWhere = $request->get('fromWhere');
            $search->toWhere = $request->get('toWhere');
            $dateDep = str_replace(array('%2F'), array('/'), $request->get('departDate'));
            $dateReturn = str_replace(array('%2F'), array('/'), $request->get('returnDate'));
            //$search->returnDate = $request->get('returnDate')??'';
            $search->whoADT = $adult;
            $search->whoKid = $kid&&$kid>0?$kid:0;
            $search->whoInf = $inf&&$inf>0?$inf:0;

            switch ($request->get('dateFormat'))
            {
                case 1:
                    $dateDep2 = \DateTime::createFromFormat('m/d/Y', $dateDep);
                    $dateReturn2 = $dateReturn&&$dateReturn!=''?\DateTime::createFromFormat('m/d/Y', $dateReturn):false;
                    break;
                case 2:
                    $dateDep2 = \DateTime::createFromFormat('d.m.Y', $dateDep);
                    $dateReturn2 = $dateReturn&&$dateReturn!=''?\DateTime::createFromFormat('d.m.Y', $dateReturn):false;
                    break;
            }
            //var_dump($dateDep);
            //var_dump($dateReturn2);
            $search->departDate = $dateDep2->format('d.m.Y');
            $search->returnDate = $dateReturn2?$dateReturn2->format('d.m.Y'):'';

            //search log
            $searchTicket = new SearchTicket();
            $searchTicket->setFromWhere($search->fromWhere);
            $searchTicket->setToWhere($search->toWhere);
            $searchTicket->setDepartingDate($dateDep2);
            $searchTicket->setReturnDepartingDate($dateReturn2!=''?$dateReturn2:null);
            $searchTicket->setAdult($search->whoADT);
            $searchTicket->setKid($search->whoKid);
            $searchTicket->setInfant($search->whoInf);
            //$searchTicket->setPriceTotal($request->get('price'));
            $searchTicket->setCreatedAt(new \DateTimeImmutable(date('Y-m-d H:i:s')));
            $searchTicket->setStatus(1);

            $entityManager->persist($searchTicket);
            $entityManager->flush();
            $searchTicketId = $responseSearch['searchProcessId'] = $searchTicket->getId();
            //$session->set('searchProcessId', $searchTicketId);

            //search flights
            $this->setApiSetting($parameterBag);
            $results = $this->getFlights($search);
            //var_dump($results);
            //exit;
            if (!$results)
            {
                throw new \Exception('No Flight found!');
            }

            $cntRsl = count($results);
            for ($iRsl=0; $iRsl<$cntRsl; $iRsl++) {//reorganize flights data
                //GMT yi tarihlere eklemiş görünüyor, GMT ye göre işlem yapmaya gerek yok, yolladğı tarih ve saat direkt basılabilir
                $dateDep3 = \DateTime::createFromFormat('d.m.Y H:i', $results[$iRsl]->departingDate.' '.$results[$iRsl]->departureTime);
                $results[$iRsl]->departingDateDisp = $dateDep3->format('D j, Y, g:i a');

                $dateArr3 = \DateTime::createFromFormat('d.m.Y H:i', $results[$iRsl]->arrivingDate.' '.$results[$iRsl]->arrivalTime);
                $results[$iRsl]->arrivingDateDisp = $dateArr3->format('D j, Y, g:i a');

                //uçuş süresi için uçuş tarihleri arasındaki farkı bul dersek GMT den dolayı hatalı sonuç verir, bu yüzden kendi gönderdiğini alıyoruz
                $results[$iRsl]->flightTimeDisp = $tools->parseHourMinute($results[$iRsl]->flightTime);
                $results[$iRsl]->flightTimeShort = $tools->parseHourMinute($results[$iRsl]->flightTime, 1);
            }

            $responseSearch['results'] = $results;
        }
        catch (\Exception $exception) {
            $responseSearch['status'] = false;
            $responseSearch['message'] = $exception->getMessage();
        }

        return $responseSearch;
    }

    public function getPnrResult($pnr)
    {
        $responsePnr = array(
            'status' => true,
            'message' => ''
        );

        try {
            $client = new \nusoap_client($this->address, 'wsdl');
            $client->soap_defencoding = 'UTF-8';
            $client->decode_utf8 = false;

            $geniusData = array(
                'parAgenturID'      => $this->agenturID,
                'parKey'            => $this->key,
                'parVorgang'        => $pnr
            );
            //var_dump($geniusData);

            $error  = $client->getError();

            if ($error and $this->debug) {
                echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
            }

            $result = $client->call('D', $geniusData);

            if ($client->fault) {
                if ($this->debug) {
                    echo "<h2>Fault</h2><pre>"; print_r($result); echo "</pre>";
                }
                throw new \Exception(serialize($client->fault));
            }
            else {
                $error = $client->getError();
                if ($error) {
                    if ($this->debug) {
                        echo "<h2>Error</h2><pre>" . $error . "</pre>";
                    }
                    throw new \Exception(serialize($error));
                }
                else {
                    if ($this->debug) {
                        echo "<h2>Main</h2>"; print_r($result);
                    }
                }
            }

            //echo '<pre>---:' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
            //var_dump($result);
            //var_dump($result['DResult']['diffgram']['NewDataSet']);
            //print_r($result);
            //var_dump($result['DResult']);
            if (isset($result['DResult']['diffgram']['NewDataSet']['BUCHUNGSDATEN'])) {
                $resultPnr['AGENTURDATEN'] = $result['DResult']['diffgram']['NewDataSet']['AGENTURDATEN'];
                $resultPnr['REISEANMELDER'] = $result['DResult']['diffgram']['NewDataSet']['REISEANMELDER'];
                $resultPnr['BUCHUNGSDATEN'] = $result['DResult']['diffgram']['NewDataSet']['BUCHUNGSDATEN'];
                $resultPnr['REISETEILNEHMER'] = $result['DResult']['diffgram']['NewDataSet']['REISETEILNEHMER'];
                $resultPnr['LEISTUNGEN'] = $result['DResult']['diffgram']['NewDataSet']['LEISTUNGEN'];
                $resultPnr['PREISZEILEN'] = $result['DResult']['diffgram']['NewDataSet']['PREISZEILEN'];
                //var_dump($resultPnr);

                $toolsGenius = new ToolGenius();
                $results['pnrResponse'] = $toolsGenius->parsePnrResult($resultPnr);
            }
            else {
                $results['error'] = $result['DResult']['diffgram']['NewDataSet']['Error'];
                throw new \Exception($results['error']['Text']);
            }

            $responsePnr['results'] = $results;
        }
        catch (\Exception $exception) {
            //var_dump($exception->getMessage());
            $responsePnr['status'] = false;
            $responsePnr['message'] = $exception->getMessage();
        }

        return $responsePnr;
    }
}