<?php

namespace App\Controller;

use App\Classes\Genius;
use App\Classes\Search;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'Genius',
        ]);
    }

    #[Route('/flight-results', name: 'app_flight_results')]
    public function flight_results(Request $request): Response
    {
        $search = new Search();
        $search->fromWhere = $request->get('fromWhere');
        $search->toWhere = $request->get('toWhere');
        $dateDep = $request->get('departDate');
        $search->offDate = $request->get('offDate');
        $search->whoADT = $request->get('whoADT');
        $search->whoKid = $request->get('whoKid');
        $search->whoInf = $request->get('whoInf');
        //var_dump($dateDep);
        $dateDep2 = \DateTime::createFromFormat('m/d/Y', $dateDep);
        $search->departDate = $dateDep2->format('d.m.Y');
        //var_dump($search->departDate);

        $genius = new Genius();
        $genius->setApiSetting($this->container->get('parameter_bag'));
        $results = $genius->getFlights($search);
        //var_dump($results);
        //exit;

        return $this->render('default/flight_results.html.twig', [
            'controller_name' => 'Genius',
            'results' => $results
        ]);
    }
}
