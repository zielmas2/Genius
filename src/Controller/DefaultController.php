<?php

namespace App\Controller;

use App\Classes\PageSetting;
use App\Entity\Ticket;
use App\Entity\TicketCustomer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        $pageSetting = new PageSetting();
        $pageSetting->metaTitle = 'Airline Tickets';

        return $this->render('default/index.html.twig', [
            'pageSetting' => $pageSetting
        ]);
    }

    #[Route('/flight-results', name: 'app_flight_results')]
    public function flight_results(): Response
    {
        $pageSetting = new PageSetting();
        $pageSetting->metaTitle = 'Flight Results';

        return $this->render('default/flight_results.html.twig', [
            'pageSetting' => $pageSetting
        ]);
    }

    #[Route('/checkout/{id}', name: 'app_checkout')]
    public function checkout(Request $request, ManagerRegistry $doctrine): Response
    {
        $pageSetting = new PageSetting();
        $pageSetting->metaTitle = 'Checkout';

        $ticketId = $request->get('id');

        $ticket = $doctrine->getRepository(Ticket::class)->findOneByTicketld($ticketId);
        //var_dump($ticket);
        $searchTicket = $ticket->getSearchTicketId();

        /*$formCustomer = $this->createFormBuilder(new TicketCustomer())
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            //->add('dueDate', DateType::class)
            //->add('save', SubmitType::class, ['label' => 'Create Task'])
            ->getForm();*/

        /*$params = '';
        $ch = curl_init('http://80.81.250.53/GeniusServerTest/GeniusServer.asmx?wsdl');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$params");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        $resultsCurl = curl_exec($ch);
        var_dump($resultsCurl);*/

        return $this->render('default/checkout.html.twig', [
            'pageSetting' => $pageSetting,
            'ticket' => $ticket,
            'searchTicket' => $searchTicket,
            //'formCustomer' => $formCustomer
        ]);
    }
}
