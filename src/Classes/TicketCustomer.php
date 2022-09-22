<?php

namespace App\Classes;

use App\Entity\Ticket;
use Doctrine\ORM\EntityManager;

class TicketCustomer
{
    public int $customerId      = 0;
    public string $name;
    public string $lastname;
    public string $identityNo   = '';
    public string $birthDay;
    public string $gender;
    public string $email        = '';
    public string $phone        = '';
    public string $address      = '';
    public int $type            = 1;//1:ERW, 2:Kid, 3: Inf

    public function save_customer(EntityManager $entityManager, Ticket $ticket, TicketCustomer $ticketCustomer, $flush=false): bool|int
    {
        $response_ = true;
        try {
            $ticketCustomerSave = new \App\Entity\TicketCustomer();
            $ticketCustomerSave->setTicketId($ticket);
            $ticketCustomerSave->setName($ticketCustomer->name);
            $ticketCustomerSave->setSurname($ticketCustomer->lastname);
            $ticketCustomerSave->setEmail($ticketCustomer->email);
            $ticketCustomerSave->setPhone($ticketCustomer->phone);
            $ticketCustomerSave->setDateOfBirth($ticketCustomer->birthDay!=''?(\DateTime::createFromFormat('Y-m-d', $ticketCustomer->birthDay)):null);
            $ticketCustomerSave->setStatus(1);

            $entityManager->persist($ticketCustomerSave);
            if ($flush)
            {
                $entityManager->flush();
                $response_ = $ticketCustomerSave->getId();
            }
        }
        catch (\Exception $exception) {
            //var_dump($exception->getMessage());
            $response_ = false;
        }

        return $response_;
    }
}