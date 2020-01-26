<?php
namespace AppBundle\Controller;

use Domain\DTO\TicketDto;
use Domain\Model\Ticket;
use Domain\UseCase\AddMessageToTicket;
use Domain\UseCase\CloseTicket;
use Domain\UseCase\OpenTicket;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends Controller
{

    /**
     * @Route("/ticket/{id}", methods={"GET"}, name="get_ticket")
     */
    public function getTicketAction(Request $request, int $id)
    {
        $username= $this->get('security.token_storage')->getToken()->getUser();
        $userRepo = $this->get('domain.user.repository');

        $loggedUser = $userRepo->loadUserByUsername($username);

        $ticketRepo = $this->get('domain.ticket.repository');

        $ticket = $ticketRepo->findByUserAndId($loggedUser, $id);

        if (!$ticket instanceof Ticket){
            $response = new JsonResponse();
            $response->setStatusCode(404);
            return $response;
        }

        return new JsonResponse($ticket->serialize());
    }

    /**
     * @Route("/ticket", methods={"POST"}, name="open_ticket")
     */
    public function openNewAction(Request $request)
    {
        $username= $this->get('security.token_storage')->getToken()->getUser();
        $userRepo = $this->get('domain.user.repository');

        $loggedUser = $userRepo->loadUserByUsername($username);

        $ticketRepo = $this->get('domain.ticket.repository');

        $data = TicketDto::fromArray($request->request->all());

        $usecase = new OpenTicket($ticketRepo);

        $ticket = $usecase->execute($loggedUser, $data);

        return new JsonResponse($ticket->serialize());
    }

    /**
     * @Route("/ticket/close/{id}", methods={"GET"}, name="close_ticket")
     */
    public function closeTicketAction(Request $request, int $id)
    {
        $username= $this->get('security.token_storage')->getToken()->getUser();
        $userRepo = $this->get('domain.user.repository');

        $loggedUser = $userRepo->loadUserByUsername($username);

        $ticketRepo = $this->get('domain.ticket.repository');

        $ticket = $ticketRepo->findByUserAndId($loggedUser, $id);

        if (!$ticket instanceof Ticket){
            $response = new JsonResponse();
            $response->setStatusCode(404);
            return $response;
        }

        $useCase = new CloseTicket($ticketRepo);
        $ticket = $useCase->execute($id, $loggedUser);

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

        $ticketRepo = $this->get('domain.ticket.repository');

        $ticket = $ticketRepo->findByUserAndId($loggedUser, $id);

        if (!$ticket instanceof Ticket){
            $response = new JsonResponse();
            $response->setStatusCode(404);
            return $response;
        }

        $data = TicketDto::fromArray($request->request->all());

        $useCase = new AddMessageToTicket($ticketRepo);
        $ticket = $useCase->execute($id,$loggedUser, $data);

        return new JsonResponse($ticket->serialize());
    }
}

