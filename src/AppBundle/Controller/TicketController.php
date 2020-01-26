<?php
namespace AppBundle\Controller;

use Domain\DTO\TicketDto;
use Domain\Model\Ticket;
use Domain\UseCase\OpenTicket;
use Domain\User\Model\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends Controller
{
    /**
     * @Route("/ticket", methods={"POST"}, name="open_ticket")
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
        $ticketRepo = $this->get('domain.ticket.repository');

        $data = TicketDto::fromArray($request->request->all());

        $usecase = new OpenTicket($ticketRepo);

        $ticket = $usecase->execute($loggedUser, $data);

        return new JsonResponse($ticket->serialize());
    }

    /**
     * @Route("/ticket/{id}", methods={"POST"}, name="add_message")
     */
    public function addMessageAction(Request $request, int $id)
    {
        $username= $this->get('security.token_storage')->getToken()->getUser();
        $userRepo = $this->get('domain.user.repository');

        $loggedUser = $userRepo->loadUserByUsername($username);

        if (!$loggedUser instanceof User){
            $response = new JsonResponse();
            $response->setStatusCode(401);
            return $response;
        }

        $ticketRepo = $this->get('domain.ticket.repository');

        $ticket = $ticketRepo->findByUserAndId($loggedUser, $id);

        if (!$ticket instanceof Ticket){
            $response = new JsonResponse();
            $response->setStatusCode(404);
            return $response;
        }

        $ticket->addMessage($request->request->get("messaggio"));

        return new JsonResponse($ticket->serialize());
    }
}

