<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Party;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OAT;
use Symfony\Component\HttpFoundation\Response;

#[OAT\Tag(
    name: 'users',
)]
final class UserController extends Controller
{
    use ValidatesRequests;
    #[OAT\Schema(
        schema: 'User',
        properties: [
            new OAT\Property(property: 'id', type: 'int'),
            new OAT\Property(property: 'name', type: 'string', maximum: 255),
            new OAT\Property(property: 'email', type: 'string', maximum: 255),
            new OAT\Property(property: 'created_at', example: '2020-04-10T06:47:43.356Z', nullable: false),
            new OAT\Property(property: 'updated_at', example: '2020-04-10T06:47:43.356Z', nullable: false),
        ]
    )]

    #[OAT\Get(
        path: '/api/users',
        tags: ['users'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'list of users',
                content: new OAT\JsonContent(
                    type: 'array',
                    items: new OAT\Items(allOf: [
                        new OAT\Schema(ref: '#/components/schemas/User'),
                    ]),
                ),
            ),
        ],
    )]
    public function index()
    {
        return User::all();
    }
}
