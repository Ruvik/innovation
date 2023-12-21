<?php

declare(strict_types=1);

namespace App\Http;

use App\Client\Entity\Id;
use App\Client\UseCase\AddCommand;
use App\Client\UseCase\AddHandler;
use App\Client\UseCase\RewardsFetcher;
use App\Client\UseCase\RewardsQuery;
use App\Client\UseCase\UpdateCommand;
use App\Client\UseCase\UpdateHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

/**
 * @psalm-suppress UnusedClass
 */
#[OA\Info(version: "1.0.0", title: "Client API")]
class ClientController extends AbstractController
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
            )
        ]
    )]
    public function addClient(Request $request, AddHandler $addHandler): Response
    {
        $command = new AddCommand(
            $request->request->get('emailVerified', true),
            $request->request->get('isBirthday', true)
        );
        $responseData = $addHandler->handle($command);

        return $this->json($responseData, Response::HTTP_CREATED);
    }

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
            )
        ]
    )]
    public function updateClient(string $id, Request $request, UpdateHandler $handler): Response
    {
        $command = new UpdateCommand(
            new Id($id),
            $request->request->get('emailVerified', false),
            $request->request->get('isBirthday', false)
        );
        $handler->handle($command);
        $responseData = [];
        return $this->json($responseData, Response::HTTP_OK);
    }

    #[Route('/client/{id}/rewards', name: 'client_rewards', methods: ['GET'])]
    #[OA\Get(
        path: "/client/{id}/rewards",
        operationId: "getClientRewards",
        summary: "Get client rewards",
        tags: ["Client Rewards"],
        parameters: [
            new OA\Parameter(
                name: "page",
                description: "Page number",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "integer", default: 1)
            ),
            new OA\Parameter(
                name: "limit",
                description: "Limit of rewards per page",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "integer", default: 10)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "A list of bonuses with pagination information",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: "data",
                                description: "Array of bonus items",
                                type: "array",
                                items: new OA\Items(
                                    properties: [
                                        new OA\Property(
                                            property: "createdAt",
                                            description: "The creation date and time of the bonus",
                                            type: "string",
                                            format: "date-time",
                                            example: "2023-12-21 16:48:26"
                                        ),
                                        new OA\Property(
                                            property: "type",
                                            description: "The type of the bonus",
                                            type: "string",
                                            example: "Hug"
                                        ),
                                        new OA\Property(
                                            property: "name",
                                            description: "The name of the bonus",
                                            type: "string",
                                            example: "Test hug bonus - 11"
                                        )
                                    ],
                                    type: "object"
                                )
                            ),
                            new OA\Property(
                                property: "meta",
                                description: "Metadata about the response",
                                properties: [
                                    new OA\Property(
                                        property: "total",
                                        description: "Total number of items available",
                                        type: "integer",
                                        example: 40
                                    ),
                                    new OA\Property(
                                        property: "limit",
                                        description: "Number of items per page",
                                        type: "integer",
                                        example: 5
                                    ),
                                    new OA\Property(
                                        property: "page",
                                        description: "Current page number",
                                        type: "integer",
                                        example: 2
                                    )
                                ],
                                type: "object"
                            )
                        ],
                        type: "object"
                    )
                )
            )
        ]
    )]
    public function getClientRewards(string $id, Request $request, RewardsFetcher $fetcher): Response
    {
        $page = (int)$request->query->get('page', 1);
        $limit = (int)$request->query->get('limit', 10);

        $query = new RewardsQuery(new Id($id), $limit, $page);

        return $this->json($fetcher->fetch($query), Response::HTTP_OK);
    }
}
