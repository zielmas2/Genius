<?php

namespace App\Controller;

use App\Classes\FlightResult;
use App\Classes\Genius;
use App\Classes\GeniusSale;
use App\Classes\ResponseZy;
use App\Entity\SearchTicket;
use App\Entity\Ticket;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AjaxController extends AbstractController
{


    #[Route('/ajx-flight-results', name: 'app_ajax_flight_results')]
    public function flight_results(Request $request, ManagerRegistry $doctrine, RequestStack $requestStack): Response
    {
        $response = new Response();
        $responseZy = new ResponseZy();

        try {
            $entityManager = $doctrine->getManager();
            $session = $requestStack->getSession();
            $genius = new Genius();

            $session->set('searchProcessId', 0);

            $flightResults = $genius->searchFromHttpRequest($request, $entityManager, $session, $this->container->get('parameter_bag'));
            if (!$flightResults['status']) {
                throw new \Exception($flightResults['message']);
            }

            $session->set('searchProcessId', $flightResults['searchProcessId']);
            $responseZy->results['searchProcessId'] = $flightResults['searchProcessId'];
            $responseZy->results['flights'] = $flightResults['results'];

        }
        catch (\Exception $exception) {
            $responseZy->status = false;
            $responseZy->message = $exception->getMessage();
        }

        $response->setContent($responseZy->send_json());
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(200);

        return $response;
    }

    #[Route('/ajx-send-to-sale', name: 'app_ajax_send_to_sale')]
    public function send_to_sale(ManagerRegistry $doctrine, Request $request, RequestStack $requestStack): Response
    {
        $response = new Response();
        $responseZy = new ResponseZy();

        try {

            $entityManager = $doctrine->getManager();
            $session = $requestStack->getSession();

            $dateDep1 = \DateTime::createFromFormat('d.m.Y', $request->get('departingDate'));

            $searchTicketId = $request->get('sId');
            $flightNumber = $request->get('fNo');
            $flightId = $request->get('fId');

            if ($session->get('searchProcessId')!=$searchTicketId) {//veri ile oynanmamış olsun
                throw new \Exception('No valid data! Please refresh the page then continue process');
            }

            $searchTicket = $doctrine->getRepository(SearchTicket::class)->find($searchTicketId);
            //var_dump($searchTicket);exit;

            //sayfada çok beklemiş ve uçuş bilgilerinin değişmiş olma ihtimaline karşı (koltuk satılmış, fiyat değişmiş, vb.) seçilen uçuşun tekrar sorgulanması
            $genius = new Genius();
            $flightResults = $genius->searchFromHttpRequest($request, $entityManager, $session, $this->container->get('parameter_bag'));
            //var_dump($flightResults);
            //exit;
            if (!$flightResults || !$flightResults['status'])
            {
                throw new \Exception('No Flight found!');
            }

            $flight = new FlightResult();
            foreach ($flightResults['results'] as $flightResult)
            {
                //var_dump($flightResult);
                if ($flightResult->flightNumber==$request->get('fNo'))
                {
                    $flight = $flightResult;
                    break;
                }
            }

            if (!$flight) {
                throw new \Exception('Couldn\'t find Flight Number!');
            }

            //bilet bilgileri yazılıyor

            //kalkış tarih ve saati ayarlanıyor
            $departureDateTime = \DateTime::createFromFormat('d.m.Y H:i', ($flight->getDepartingDate().' '.$flight->getDepartureTime()));
            //iniş tarih ve saati ayarlanıyor
            $arrivingDateTime = \DateTime::createFromFormat('d.m.Y H:i', ($flight->getArrivingDate().' '.$flight->getArrivalTime()));
            //var_dump($departureDateTime);exit;

            $ticket = new Ticket();
            $ticket->setSearchTicketId($searchTicket);
            $ticket->setDepartureDate($departureDateTime);
            $ticket->setArrivingDate($arrivingDateTime);
            $ticket->setCarrier($flight->getCarrier());
            $ticket->setDirection($flight->isHasTwoWay()?2:1);
            $ticket->setAirline($flight->getAirline());
            $ticket->setFlightNumber($flight->flightNumber);
            $ticket->setBaggage($flight->getFreeBag());
            $ticket->setFlightId($flight->getFlightID());
            $ticket->setFlightTime($flight->getFlightTime());
            $ticket->setAdultPrice($flight->getPriceADT());
            $ticket->setPriceKid($flight->getPriceKid());
            $ticket->setPriceInf($flight->getPriceInf());
            $ticket->setTax($flight->getTax());
            $ticket->setCurrency($flight->getCurrency());
            $ticket->setCreatedAt(new \DateTimeImmutable(date('Y-m-d')));
            $ticket->setStatus(1);

            $entityManager->persist($ticket);
            $entityManager->flush();
            $results['processId'] = $ticket->getId();

            $responseZy->results = $results;
        }
        catch (\Exception $exception) {
            $responseZy->status = false;
            $responseZy->message = $exception->getMessage();
        }

        $response->setContent($responseZy->send_json());
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(200);

        return $response;
    }

    #[Route('/ajx-sale', name: 'app_ajax_sale')]
    public function sale(ManagerRegistry $doctrine, Request $request, RequestStack $requestStack): Response
    {
        $response = new Response();
        $responseZy = new ResponseZy();

        try {

            $entityManager = $doctrine->getManager();
            $session = $requestStack->getSession();

            //burayı aşağıya al
            $geniusSale = new GeniusSale();
            $geniusSale->sale($this->container->get('parameter_bag'), $request);
            exit;//geçici

            //sayfada çok beklemiş ve uçuş bilgilerinin değişmiş olma ihtimaline karşı (koltuk satılmış, fiyat değişmiş, vb.) seçilen uçuşun tekrar sorgulanması
            $genius = new Genius();
            $flightResults = $genius->searchFromHttpRequest($request, $entityManager, $session, $this->container->get('parameter_bag'));
            //var_dump($flightResults);
            //exit;
            if (!$flightResults || !$flightResults['status'])
            {
                throw new \Exception('No Flight found!');
            }

            $flight = new FlightResult();
            foreach ($flightResults['results'] as $flightResult)
            {
                //var_dump($flightResult);
                if ($flightResult->flightNumber==$request->get('fNo'))
                {
                    $flight = $flightResult;
                    break;
                }
            }

            if (!$flight) {
                throw new \Exception('Couldn\'t find Flight Number!');
            }



        }
        catch (\Exception $exception) {
            $responseZy->status = false;
            $responseZy->message = $exception->getMessage();
        }

        $response->setContent($responseZy->send_json());
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(200);

        return $response;
    }
}
