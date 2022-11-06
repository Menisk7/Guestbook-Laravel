<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\PartyResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\PartyService;
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
        return User::all()->toJson();
    }
}
