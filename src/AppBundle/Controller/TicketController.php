<?php
namespace AppBundle\Controller;

use Domain\DTO\TicketDto;
use Domain\Model\Ticket;
use Domain\UseCase\AddMessageToTicket;
use Domain\UseCase\AdminAddMessageToTicket;
use Domain\UseCase\AssignTicket;
use Domain\UseCase\CloseTicket;
use Domain\UseCase\OpenTicket;
use Domain\User\Model\User;
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
        $loggedUser = $this->getLoggedUser();

        $ticket = $this->getTicket($id, $loggedUser);

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
        $loggedUser = $this->getLoggedUser();

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
        $loggedUser = $this->getLoggedUser();

        $useCase = new CloseTicket($this->get('domain.ticket.repository'));
        $ticket = $useCase->execute($id, $loggedUser);

        if (!$ticket){
            $response = new JsonResponse();
            $response->setStatusCode(404);
            return $response;
        }

        return new JsonResponse($ticket->serialize());
    }

    /**
     * @Route("/ticket/assign/{id}", methods={"GET"}, name="assign_ticket")
     */
    public function assignTicketAction(Request $request, int $id)
    {
        $loggedUser = $this->getLoggedUser();

        $ticketRepo = $this->get('domain.ticket.repository');

        $useCase = new AssignTicket($ticketRepo);

        try {
            $ticket = $useCase->execute($id, $loggedUser);
        } catch (\Exception $e){
            $response = new JsonResponse();
            $response->setStatusCode(404);
            return $response;
        }

        return new JsonResponse($ticket->serialize());
    }

    /**
     * @Route("/ticket/{id}", methods={"POST"}, name="add_message")
     */
    public function addMessageAction(Request $request, int $id)
    {
        $loggedUser = $this->getLoggedUser();

        $data = TicketDto::fromArray($request->request->all());

        $useCase = new AddMessageToTicket($this->get('domain.ticket.repository'));
        $ticket = $useCase->execute($id,$loggedUser, $data);

        if (!$ticket instanceof Ticket){
            $response = new JsonResponse();
            $response->setStatusCode(404);
            return $response;
        }

        return new JsonResponse($ticket->serialize());
    }

    /**
     * @Route("/admin/ticket/{id}", methods={"POST"}, name="admin_add_message")
     */
    public function adminAddMessageAction(Request $request, int $id)
    {
        $loggedUser = $this->getLoggedUser();

        $data = TicketDto::fromArray($request->request->all());

        $useCase = new AdminAddMessageToTicket($this->get('domain.ticket.repository'));
        $ticket = $useCase->execute($id,$loggedUser, $data);

        if (!$ticket instanceof Ticket){
            $response = new JsonResponse();
            $response->setStatusCode(404);
            return $response;
        }

        return new JsonResponse($ticket->serialize());
    }

    private function getLoggedUser(): User
    {
        $username = $this->get('security.token_storage')->getToken()->getUser();
        $userRepo = $this->get('domain.user.repository');

        $loggedUser = $userRepo->loadUserByUsername($username);

        return $loggedUser;
    }

    /**
     * @param int $id
     * @param User $loggedUser
     * @return mixed
     */
    private function getTicket(int $id, User $loggedUser)
    {
        $ticketRepo = $this->get('domain.ticket.repository');

        $ticket = $ticketRepo->findByUserAndId($loggedUser, $id);

        return $ticket;
    }
}

