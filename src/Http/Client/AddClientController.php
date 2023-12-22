<?php

declare(strict_types=1);

namespace App\Http\Client;

use App\Client\UseCase\AddCommand;
use App\Client\UseCase\AddHandler;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use TypeError;

/**
 * @psalm-suppress UnusedClass
 */
class AddClientController extends AbstractController
{
    #[Route('/client', name: 'add_client', methods: ['POST'])]
    #[OA\Post(
        path: "/client",
        operationId: "addClient",
        summary: "Add a new client",
        requestBody: new OA\RequestBody(
            description: "Client data",
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "emailVerified", type: "boolean"),
                        new OA\Property(property: "isBirthday", type: "boolean")
                    ],
                    type: "object"
                )
            )
        ),
        tags: ["Client"],
        responses: [
            new OA\Response(
                response: 201,
                description: "Client created",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: "id", description: "Unique ID of client", type: "string"),
                            new OA\Property(
                                property: "appliedIds",
                                description: "List of bonus IDs that have been applied to the client",
                                type: "array",
                                items: new OA\Items(type: "string")
                            )
                        ],
                        type: "object"
                    )
                )
            ),
            new OA\Response(
                response: 422,
                description: "Unprocessable Entity - Invalid type arguments",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: "message",
                                type: "string",
                                example: "Invalid type arguments"
                            )
                        ],
                        type: "object"
                    )
                )
            ),
            new OA\Response(
                response: 400,
                description: "Bad Request - Domain specific error",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: "message",
                                type: "string",
                                example: "Bad request or domain specific message"
                            )
                        ],
                        type: "object"
                    )
                )
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: "message",
                                type: "string",
                                example: "Internal Server Error"
                            )
                        ],
                        type: "object"
                    )
                )
            )
        ]
    )]
    public function __invoke(Request $request, AddHandler $addHandler): Response
    {
        $jsonData = json_decode($request->getContent(), true);

        try {
            $command = new AddCommand(
                $jsonData['emailVerified'] ?? false,
                $jsonData['isBirthday'] ?? false
            );
        } catch (TypeError $e) {
            throw new InvalidArgumentException('Invalid type arguments', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $responseData = $addHandler->handle($command);

        return $this->json($responseData, Response::HTTP_CREATED);
    }
}
