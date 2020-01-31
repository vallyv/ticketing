<?php
namespace AppBundle\Controller;

use Domain\DTO\TicketDto;
use Domain\Model\Ticket;
use Domain\ReadModel\TicketReadModel;
use Domain\UseCase\AddMessageToTicket;
use Domain\UseCase\AdminAddMessageToTicket;
use Domain\UseCase\AdminCloseTicket;
use Domain\UseCase\AdminReassignTicket;
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

        $rm = TicketReadModel::create($ticket);

        return new JsonResponse($rm->serialize());
    }

    /**
     * @Route("/ticket", methods={"POST"}, name="open_ticket")
     */
    public function openNewAction(Request $request)
    {
        $loggedUser = $this->getLoggedUser();
        $ticketRepo = $this->get('domain.ticket.repository');
        $sender = $this->get('domain.adminNotifications.sender');
        $data = TicketDto::fromArray($request->request->all());

        $usecase = new OpenTicket($ticketRepo, $sender);
        $ticket = $usecase->execute($loggedUser, $data);
        $rm = TicketReadModel::create($ticket);

        return new JsonResponse($rm->serialize());
    }

    /**
     * @Route("/ticket/close/{id}", methods={"GET"}, name="close_ticket")
     */
    public function closeTicketAction(Request $request, int $id)
    {
        $loggedUser = $this->getLoggedUser();
        $sender = $this->get('domain.adminNotifications.sender');

        $useCase = new CloseTicket($this->get('domain.ticket.repository'), $sender);
        $ticket = $useCase->execute($id, $loggedUser);

        if (!$ticket){
            $response = new JsonResponse();
            $response->setStatusCode(404);
            return $response;
        }
        $rm = TicketReadModel::create($ticket);

        return new JsonResponse($rm->serialize());
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
            $rm = TicketReadModel::create($ticket);
        } catch (\Exception $e){
            $response = new JsonResponse();
            $response->setStatusCode(404);
            return $response;
        }

        return new JsonResponse($rm->serialize());
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

        $rm = TicketReadModel::create($ticket);

        return new JsonResponse($rm->serialize());
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

        $rm = TicketReadModel::create($ticket);

        return new JsonResponse($rm->serialize());
    }

    /**
     * @Route("/admin/ticket/close/{id}", methods={"GET"}, name="admin_close_ticket")
     */
    public function adminCloseTicketAction(Request $request, int $id)
    {
        $loggedUser = $this->getLoggedUser();

        $useCase = new AdminCloseTicket($this->get('domain.ticket.repository'));
        $ticket = $useCase->execute($id, $loggedUser);

        if (!$ticket){
            $response = new JsonResponse();
            $response->setStatusCode(404);
            return $response;
        }

        $rm = TicketReadModel::create($ticket);

        return new JsonResponse($rm->serialize());
    }

    private function getLoggedUser(): User
    {
        $username = $this->get('security.token_storage')->getToken()->getUser();
        $userRepo = $this->get('domain.user.repository');

        $loggedUser = $userRepo->loadUserByUsername($username);

        return $loggedUser;
    }

    /**
     * @Route("/admin/ticket/reassign/{id}/{username}", methods={"GET"}, name="admin_reassign_ticket")
     */
    public function adminReassignTicketAction(Request $request, int $id, string  $username)
    {
        $loggedUser = $this->getLoggedUser();

        $userRepo = $this->get('domain.user.repository');

        $user = $userRepo->loadUserByUsername($username);

        if(!$user){
            $response = new JsonResponse();
            $response->setStatusCode(404);
            return $response;
        }

        $ticketRepo = $this->get('domain.ticket.repository');

        $useCase = new AdminReassignTicket($ticketRepo);

        try {
            $ticket = $useCase->execute($id, $loggedUser, $user);
            $rm = TicketReadModel::create($ticket);

        } catch (\Exception $e){
            $response = new JsonResponse();
            $response->setStatusCode(404);
            return $response;
        }

        return new JsonResponse($rm->serialize());
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

