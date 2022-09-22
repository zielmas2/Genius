<?php

namespace App\Classes;

use App\Entity\Ticket;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;

class GeniusSale extends Genius
{
    public array    $teilnehmer;
    public array    $leistungen;
    public array    $reiseanmelder;
    public string   $zahlung          = '';

    public function sale(ParameterBagInterface $parameter, EntityManager $entityManager, Request $request, FlightResult $flight):ResponseZy
    {
        //var_dump($flight);exit;
        $responseZy = new ResponseZy();

        try {
            $this->debug = true;//geçici

            $pnr = false;
            $supplierResponsePrice = null;
            $ticketCustomers = false;
            $ticketId = $request->get('tId');//controller da verinin doğruluğu kontrol edildi

            $ticket = $entityManager->getRepository(Ticket::class)->find($ticketId);

            /*$resultXml = '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><BResponse xmlns="http://tempuri.org/"><BResult><xs:schema id="NewDataSet" xmlns="" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:msdata="urn:schemas-microsoft-com:xml-msdata"><xs:element name="NewDataSet" msdata:IsDataSet="true" msdata:UseCurrentLocale="true"><xs:complexType><xs:choice minOccurs="0" maxOccurs="unbounded"><xs:element name="Error"><xs:complexType><xs:sequence><xs:element name="Nummer" type="xs:string" minOccurs="0" /><xs:element name="Text" type="xs:string" minOccurs="0" /></xs:sequence></xs:complexType></xs:element></xs:choice></xs:complexType></xs:element></xs:schema><diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1"><NewDataSet xmlns=""><Error diffgr:id="Error1" msdata:rowOrder="0"><Nummer>1960</Nummer><Text>Buchung bereits vorhanden.  #VORGANGSNUMMER : A0585570</Text></Error></NewDataSet></diffgr:diffgram></BResult></BResponse></soap:Body></soap:Envelope>';*/
            /*$resultXml = '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><BResponse xmlns="http://tempuri.org/"><BResult><xs:schema id="NewDataSet" xmlns="" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:msdata="urn:schemas-microsoft-com:xml-msdata"><xs:element name="NewDataSet" msdata:IsDataSet="true" msdata:UseCurrentLocale="true"><xs:complexType><xs:choice minOccurs="0" maxOccurs="unbounded"><xs:element name="Result"><xs:complexType><xs:sequence><xs:element name="Vorgang" type="xs:string" minOccurs="0" /><xs:element name="Reisepreis" type="xs:string" minOccurs="0" /></xs:sequence></xs:complexType></xs:element></xs:choice></xs:complexType></xs:element></xs:schema><diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1"><NewDataSet xmlns=""><Result diffgr:id="Result1" msdata:rowOrder="0"><Vorgang>A0585578</Vorgang><Reisepreis>159.9</Reisepreis></Result></NewDataSet></diffgr:diffgram></BResult></BResponse></soap:Body></soap:Envelope>';*/

            $this->setApiSetting($parameter);

            $customerNames = $request->get('name');
            $customerLastNames = $request->get('lastname');
            $customerGenders = $request->get('gender');
            $customerBirthDays = $request->get('birthday');
            $customerEmail = $request->get('email');
            $customerPhone = $request->get('phone');
            $customerTypes = $request->get('cType');
            $cntCustomer = count($customerNames);
            //$teilnehmer_ = array();
            for ($iCn=0; $iCn<$cntCustomer; $iCn++)
            {
                $ticketCustomer = new TicketCustomer();
                $ticketCustomer->name = $customerNames[$iCn];
                $ticketCustomer->lastname = $customerLastNames[$iCn];
                $ticketCustomer->gender = isset($customerGenders[$iCn])?$customerGenders[$iCn]:'H';
                $ticketCustomer->birthDay = isset($customerBirthDays[$iCn])&&$customerBirthDays[$iCn]!=''?$customerBirthDays[$iCn]:'';
                $ticketCustomer->type = $customerTypes[$iCn];
                if ($iCn===0)
                {
                    $ticketCustomer->email = $customerEmail;
                    $ticketCustomer->phone = $customerPhone;
                }
                //var_dump($ticketCustomer);
                $ticketCustomers[] = $ticketCustomer;
            }
            //var_dump($ticketCustomers);

            //bilet satışı olsa da olmasa da Müşteri bilgisini alıyoruz, geri dönüş sağlayabilmek için
            $ticketCustomerSave = new TicketCustomer();
            $ticketCustomers[0]->customerId = $ticketCustomerSave->save_customer($entityManager, $ticket, $ticketCustomers[0], true);//sadece yetişkin 1 i yazıyoruz.  flush diğer verileri de aktarır, istersen entity yi değiştir
            //var_dump($ticketCustomers);

            $parTeilnehmer = '';
            for ($iCn=0; $iCn<$cntCustomer; $iCn++)
            {
                $ticketCustomer = false;
                $ticketCustomer = $ticketCustomers[$iCn];
                $parTeilnehmer .= '
                    <Teilnehmer>
                        <ZuordnungsID>'.($iCn+1).'</ZuordnungsID>
                        <Anrede>'.'H'.'</Anrede>
                        <Vorname>'.$ticketCustomer->name.'</Vorname>
                        <Nachname>'.$ticketCustomer->lastname.'</Nachname>
                        <Geburtsdatum>'.($ticketCustomer->birthDay?'':'').'</Geburtsdatum>
                    </Teilnehmer>
';
            }

            //<Datum>'.\DateTime::createFromFormat('Y-m-d', $flight->getDepartingDate()).'</Datum>
            $parLeistungen = '';
            $parLeistungen .= '
                    <Flugauswahl>
                        <FlugID>'.$flight->getFlightID().'</FlugID>
                        <Datum>'.$flight->getDepartingDate().'</Datum>
                        <Carrier>'.$flight->getCarrier().'</Carrier>
                        <Flugnummer>'.$flight->getFlightNumber().'</Flugnummer>
                        <Von>'.$flight->getFromWhere().'</Von>
                        <Nach>'.$flight->getToWhere().'</Nach>
                        <Tarif>'.''.'</Tarif>
                        <Preis_ERW>'.$flight->getPriceADT().'</Preis_ERW>
                        <Preis_KIND>'.$flight->getPriceKid().'</Preis_KIND>
                        <Preis_BABY>'.$flight->getPriceInf().'</Preis_BABY>
                        <TnZuordnung></TnZuordnung>
                    </Flugauswahl>
';
            if (isset($flight->returnFlight))
            {
                $parLeistungen .= '
                    <Flugauswahl>
                        <FlugID>'.$flight->returnFlight->getFlightID().'</FlugID>
                        <Datum>'.$flight->returnFlight->getDepartingDate().'</Datum>
                        <Carrier>'.$flight->returnFlight->getCarrier().'</Carrier>
                        <Flugnummer>'.$flight->returnFlight->getFlightNumber().'</Flugnummer>
                        <Von>'.$flight->returnFlight->getFromWhere().'</Von>
                        <Nach>'.$flight->returnFlight->getToWhere().'</Nach>
                        <Tarif>'.''.'</Tarif>
                        <Preis_ERW>'.$flight->returnFlight->getPriceADT().'</Preis_ERW>
                        <Preis_KIND>'.$flight->returnFlight->getPriceKid().'</Preis_KIND>
                        <Preis_BABY>'.$flight->returnFlight->getPriceInf().'</Preis_BABY>
                        <TnZuordnung></TnZuordnung>
                    </Flugauswahl>
';
            }

            $reiseanmelder = '
                    <Reiseanmelder>
                        <Anrede>H</Anrede>
                        <Vorname>airtuerk</Vorname>
                        <Nachname>Service GmbH</Nachname>
                        <Strasse>Musterstr. 6</Strasse>
                        <PLZ>13509</PLZ>
                        <Ort>Frankfurt am Main</Ort>
                        <Land>DE</Land>
                        <Telefon1>+491112223333</Telefon1>
                        <Telefon2>+491112223334</Telefon2>
                        <Telefax>+491112223333</Telefax>
                        <Email>ticket@airtuerk.de</Email>
                    </Reiseanmelder>
';

            $param = '<?xml version="1.0" encoding="utf-8"?>
<S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
    <SOAP-ENV:Header/>
    <S:Body xmlns:ns2="http://tempuri.org/" xmlns:xs="http://www.w3.org/2001/XMLSchema">
        <ns2:B>
            <ns2:parAgenturID>'.$this->getAgenturID().'</ns2:parAgenturID>
            <ns2:parTeilnehmer>
                <![CDATA[<Daten>'.$parTeilnehmer.'</Daten>]]>
            </ns2:parTeilnehmer>
            <ns2:parLeistungen>
                <![CDATA[<Daten>'.$parLeistungen.'</Daten>]]>
            </ns2:parLeistungen>
            <ns2:parReiseanmelder>
                <![CDATA[<Daten>'.$reiseanmelder.'</Daten>]]>
            </ns2:parReiseanmelder>
            <ns2:parFix></ns2:parFix>
            <ns2:parKey>'.$this->getKey().'</ns2:parKey>
        </ns2:B>
    </S:Body>
</S:Envelope>';
            //print $param;

            $ch = curl_init($this->getAddress());
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            $resultXml = curl_exec($ch);
            //var_dump($resultXml);
            //print_r($resultXml);

            /*$resultsObj = json_encode($results);
            var_dump($resultsObj);
            $arrayResult = json_encode(simplexml_load_string($results, 'SimpleXMLElement', LIBXML_NOCDATA));
            var_dump($arrayResult);*/

            $xml = new \SimpleXMLElement($resultXml);
            $error = $xml->xpath('//Error');
            $result = $xml->xpath('//Result');
            //var_dump($error);
            //var_dump($result);
            if (isset($error[0]->Text)) {
                $forPnr = explode('VORGANGSNUMMER :',  $error[0]->Text);
                if (isset($forPnr[1]))
                {
                    $pnr = trim($forPnr[1]);

                    //TODO: PNR dan önceki müşteriyi bulup işlemlerini yap
                    //TODO: yöneticiyi bilgilendir

                    throw new \Exception('This flight added to system. Please contact our customer sevice.');
                }
                else {
                    throw new \Exception($error[0]->Text);
                }
            }
            else if (isset($result[0]->Vorgang)) {
                $pnr = $result[0]->Vorgang;
                $supplierResponsePrice = (float)str_replace(',', '.', $result[0]->Reisepreis);
            }
            //var_dump($pnr);

            if ($pnr || $pnr!='') {
                $toolGenius = new ToolGenius();
                $responseSale = $toolGenius->sale_success($entityManager, $ticket, $ticketId, $pnr, $supplierResponsePrice, $ticketCustomers);
                //var_dump($responseSale);
                if (!$responseSale->status)
                {
                    throw new \Exception('Sale unsuccessful');
                }

                $responseZy->results['pnr'] = $pnr;
                $responseZy->results['ticketId'] = $ticketId;
            }

            $entityManager->flush();

            //return $result;
        }
        catch (\Exception $exception) {
            $responseZy->status = false;
            $responseZy->message = $exception->getMessage();
        }

        //var_dump($responseZy);
        return $responseZy;
    }
}