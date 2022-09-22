<?php

namespace App\Controller;

use App\Classes\PageSetting;
use App\Classes\Search;
use App\Classes\Tool;
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
    public function flight_results(Request $request): Response
    {
        $pageSetting = new PageSetting();
        $pageSetting->metaTitle = 'Flight Results';

        $search = new Search();
        $search->fromWhere = $request->get('fromWhere');
        $search->toWhere = $request->get('toWhere');
        $search->departDate = $request->get('departDate');
        $search->returnDate = $request->get('returnDate');

        return $this->render('default/flight_results.html.twig', [
            'pageSetting' => $pageSetting,
            'search' => $search
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

        if ($ticket->getPnrNo()!='')
        {
            $this->addFlash(
                'notice',
                'This Ticket has PNR!'
            );

            return $this->redirectToRoute('app_index');
        }

        if (!$ticket->getId())
        {
            $this->addFlash(
                'notice',
                'Ticket not found!'
            );

            return $this->redirectToRoute('app_index');
        }

        $tools = new Tool();

        $ticket->flightTimeDisp = $tools->parseHourMinute($ticket->getFlightTime(), 1);

        $cntAdult = $searchTicket->getAdult();
        $cntKid = $searchTicket->getKid();
        $cntInf = $searchTicket->getInfant();

        return $this->render('default/checkout.html.twig', [
            'pageSetting' => $pageSetting,
            'ticket' => $ticket,
            'searchTicket' => $searchTicket,
            'cntAdult' => $cntAdult,
            'cntKid' => $cntKid,
            'cntInf' => $cntInf
        ]);
    }

    #[Route('/booking-result/{id}', name: 'app_booking_result')]
    public function booking_result(Request $request, ManagerRegistry $doctrine): Response
    {
        $pageSetting = new PageSetting();
        $pageSetting->metaTitle = 'Booking Result';

        $ticketId = $request->get('id');

        $ticket = $doctrine->getRepository(Ticket::class)->findOneByTicketld($ticketId);
        $searchTicket = $ticket->getSearchTicketId();
        $customer = $doctrine->getRepository(TicketCustomer::class)->findOneBy(array('ticket_id'=>$ticket));

        return $this->render('default/booking_result.html.twig', [
            'pageSetting' => $pageSetting,
            'ticket' => $ticket,
            'searchTicket' => $searchTicket,
            'customer' => $customer
        ]);
    }

    #[Route('/list-tickets', name: 'app_list_tickets')]
    public function list_tickets(ManagerRegistry $doctrine): Response
    {
        $pageSetting = new PageSetting();
        $pageSetting->metaTitle = 'List Tickets';

        $customers = $doctrine->getRepository(TicketCustomer::class)->findBy(array(), array('id'=>'DESC'));
        $tickets = $doctrine->getRepository(Ticket::class)->findByPnr();
        //var_dump($tickets);

        $cntTicket = count($tickets);
        for ($iT=0; $iT<$cntTicket; $iT++)
        {
            foreach ($customers as $customer)
            {
                $tickets[$iT]->customerName = '';
                $tickets[$iT]->customerLastName = '';
                if ($customer->getTicketId() && $customer->getTicketId()->getId()==$tickets[$iT]->getId())
                {
                    $tickets[$iT]->customerName = $customer->getName();
                    $tickets[$iT]->customerLastName = $customer->getSurname();
                    break;
                }
            }
        }
        //var_dump($tickets);

        return $this->render('default/list_tickets.html.twig', [
            'pageSetting' => $pageSetting,
            'tickets' => $tickets
        ]);
    }
}
