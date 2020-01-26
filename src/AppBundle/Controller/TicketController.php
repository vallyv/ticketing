<?php
namespace AppBundle\Controller;

use Domain\DTO\TicketDto;
use Domain\Model\Ticket;
use Domain\User\Model\User;
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
        $username= $this->get('security.token_storage')->getToken()->getUser();
        $userRepo = $this->get('domain.user.repository');

        $loggedUser = $userRepo->loadUserByUsername($username);

        if (!$loggedUser instanceof User){
            $response = new JsonResponse();
            $response->setStatusCode(401);
            return $response;
        }

        $ticket = Ticket::OpenTicket($loggedUser, TicketDto::fromArray($request->request->all()));
        $ticketRepo = $this->get('domain.ticket.repository');

        $ticketRepo->save($ticket);

        return new JsonResponse($ticket->serialize());
    }
}

