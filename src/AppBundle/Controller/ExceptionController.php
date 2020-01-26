<?php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

/**
 * Custom ExceptionController that renders to json
 *
 * Configure it like so:
app.json_exception_controller:
public: true
class: App\Controller\ExceptionController
arguments:
- '@fos_rest.exception.codes_map'
 */
class ExceptionController
{

    /**
     * Converts an Exception to a Response.
     *
     * @param Request                   $request
     * @param \Exception|\Throwable     $exception
     * @param DebugLoggerInterface|null $logger
     *
     * @throws \InvalidArgumentException
     *
     * @return Response
     */
    public function showAction(Request $request, $exception, DebugLoggerInterface $logger = null)
    {
        return new Response(
            json_encode(
                ['error' => $exception->getMessage()],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            ),
            //$exception->getStatusCode(),
            ['Content-type' => 'application/json']
        );
    }


}
