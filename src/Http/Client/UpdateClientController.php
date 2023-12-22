<?php

declare(strict_types=1);

namespace App\Http\Client;

use App\Client\Entity\Id;
use App\Client\UseCase\AddCommand;
use App\Client\UseCase\UpdateCommand;
use App\Client\UseCase\UpdateHandler;
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
class UpdateClientController extends AbstractController
{
    #[Route('/client/{id}', name: 'update_client', methods: ['PUT'])]
    #[OA\Put(
        path: "/client/{id}",
        operationId: "updateClient",
        summary: "Update client information",
        requestBody: new OA\RequestBody(
            description: "Client updated data",
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
                response: 200,
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
                response: 404,
                description: "Not Found - Entity not found",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: "message",
                                type: "string",
                                example: "Entity not found"
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
    public function __invoke(string $id, Request $request, UpdateHandler $handler): Response
    {
        $jsonData = json_decode($request->getContent(), true);

        try {
            $command = new UpdateCommand(
                new Id($id),
                $jsonData['emailVerified'] ?? false,
                $jsonData['isBirthday'] ?? false
            );
        } catch (TypeError $e) {
            throw new InvalidArgumentException('Invalid type arguments', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $responseData = $handler->handle($command);
        return $this->json($responseData, Response::HTTP_OK);
    }
}
