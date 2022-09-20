<?php

namespace App\Classes;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;

class GeniusSale extends Genius
{
    public array $teilnehmer;
    public array $leistungen;
    public array $reiseanmelder;
    public array $zahlung;

    public function sale(ParameterBagInterface $parameter, Request $request)
    {
        try {
            $client = new \nusoap_client($this->getAddress(), 'wsdl');
            $client->soap_defencoding = 'UTF-8';
            $client->decode_utf8 = false;

            $this->setApiSetting($parameter);

            $customerNames = $request->get('name');
            $customerLastNames = $request->get('lastname');
            $cntCustomer = count($customerNames);
            for ($iCn=0; $iCn<$cntCustomer; $iCn++)
            {
                $teilnehmerGT = new GeniusSaleTeilnehmer();
                $teilnehmerGT->ZuordnungsId = ($iCn+1);
                $teilnehmerGT->Anrede = 'H';
                $teilnehmerGT->Vorname = $customerNames[$iCn];
                $teilnehmerGT->Nachname = $customerLastNames[$iCn];
                $teilnehmerGT->Geburtsdatum = '';
                $this->teilnehmer[] = (array)$teilnehmerGT;
            }
            var_dump($this->teilnehmer);

            $geniusSaleData = array(
                'parAgenturID'      => $this->getAgenturID(),
                'parKey'            => $this->getKey(),
                'parTeilnehmer'     => $this->teilnehmer,
                'parLeistungen'     => '',
                'parReiseanmelder'  => '',
                'parZahlung'        => ''
            );
            //var_dump($geniusSaleData); exit;

            $error  = $client->getError();

            if ($error and $this->debug) {
                echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
            }

            $result = $client->call('B', $geniusSaleData);
            //echo '<pre>---:' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
            var_dump($result);
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
}