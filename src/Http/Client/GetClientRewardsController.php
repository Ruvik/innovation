<?php

declare(strict_types=1);

namespace App\Http\Client;

use App\Client\Entity\Id;
use App\Client\UseCase\RewardsFetcher;
use App\Client\UseCase\RewardsQuery;
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
class GetClientRewardsController extends AbstractController
{
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
    public function getClientRewards(string $id, Request $request, RewardsFetcher $fetcher): Response
    {
        $page = (int)$request->query->get('page', 1);
        $limit = (int)$request->query->get('limit', 10);

        $query = new RewardsQuery(new Id($id), $limit, $page);

        return $this->json($fetcher->fetch($query), Response::HTTP_OK);
    }
}
