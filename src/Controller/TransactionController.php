<?php declare(strict_types = 1);

namespace App\Controller;

use App\Messenger\Middleware\TransactionAllowedStamp;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use App\Messenger\Message\PostTransaction;

/**
 * Class TransactionController
 *
 * @package App\Controller
 * @Route("/api/v1", name="api_v1_")
 */
class TransactionController extends AbstractFOSRestController
{
    /**
     * @Rest\Post("/transactions")
     * @ParamConverter("message", converter="command_param_converter", options={"map": {"user_id": "userId", "receiver_name": "receiverName", "receiver_account": "receiverAccount"}})
     *
     * @param MessageBusInterface $messageBus
     * @param PostTransaction     $message
     *
     * @return Response
     */
    public function postTransaction(MessageBusInterface $messageBus, PostTransaction $message): Response
    {
        $result = $messageBus->dispatch(
            (new Envelope($message))->with(new TransactionAllowedStamp($message->getUserId(), $message->getCurrency()))
        )->last(HandledStamp::class)->getResult();

        return $this->handleView($this->view(["transaction_id" => $result]));
    }
}
