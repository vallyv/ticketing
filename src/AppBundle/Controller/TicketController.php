<?php
namespace AppBundle\Controller;

use Domain\DTO\TicketDto;
use Domain\User\Model\Ticket;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends Controller
{
    /**
     * @Route("/ticket", methods={"POST"}, name="homepage")
     */
    public function openNewAction(Request $request)
    {
        //$ticket= Ticket::OpenTicket($user, TicketDto::fromArray($request->request->all());

        return new JsonResponse($request->request->all());
    }
}

