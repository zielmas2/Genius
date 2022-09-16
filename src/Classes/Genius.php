<?php

namespace App\Classes;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class Genius
{
    private string $address = 'http://80.81.250.53/GeniusServerTest/GeniusServer.asmx?wsdl';
    private string $agenturID;// = $_ENV["GENIUS_AID"];//getenv('GENIUS_AID');
    private string $key;// = $_ENV["GENIUS_KEY"];//getenv('GENIUS_KEY');
    public bool $debug = false;

    function __construct() {

    }

    public function setApiSetting(ParameterBagInterface $parameter) {
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
                'parRueckflugdatum' => $search->offDate,
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
            //var_dump($result['GVResult']);
            //var_dump($result['GVResult']['diffgram']['NewDataSet']['GV_Hinflug']);
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

            return $this->parseResults($results);
        }
        catch (\Exception $exception) {
            var_dump($exception->getMessage());
            return false;
        }
    }

    public function setFlightResults()
    {
        $flightResult = new FlightResult();

        $flightResults[0] = $flightResult;

        return $flightResults;
    }

    public function parseResults($results)
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
                $flightResult->setFlightType($result['FLUGZEUGTYP']);
                $flightResult->setFreeBag($result['FREIGEPAECK']);
                $flightResult->setFreeBagSize($result['MAX_ANZAHL_GEPAECKSTUECKE']);

                $flightResults[] = $flightResult;
            }
        }

        //$results = $result;

        return $flightResults;
    }
}