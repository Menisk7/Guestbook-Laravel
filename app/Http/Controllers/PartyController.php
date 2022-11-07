<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Party;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OAT;
use Symfony\Component\HttpFoundation\Response;
use TheSeer\Tokenizer\Exception;

#[OAT\Tag(
    name: 'parties',
)]
final class PartyController extends Controller
{
    use ValidatesRequests;


    #[OAT\Schema(
        schema: 'Party',
        properties: [
            new OAT\Property(property: 'id', type: 'int'),
            new OAT\Property(property: 'user_ids',type:'array',items: new OAT\Items(type:'integer')),
            new OAT\Property(property: 'name', type: 'string', maximum: 255),
            new OAT\Property(property: 'created_at', example: '2020-04-10T06:47:43.356Z', nullable: false),
            new OAT\Property(property: 'updated_at', example: '2020-04-10T06:47:43.356Z', nullable: false),
        ]
    )]


    #[OAT\Get(
        path: '/api/parties',
        tags: ['parties'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'list of parties',
                content: new OAT\JsonContent(
                    type: 'array',
                    items: new OAT\Items(allOf: [
                        new OAT\Schema(ref: '#/components/schemas/Party'),
                    ]),
                ),
            ),
        ],
    )]

    public function index()
    {
        return Party::all();
    }

    #[OAT\Post(
        path: '/api/parties',
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(properties: [
                new OAT\Property(property: 'name', type: 'string', maximum: 255),
                new OAT\Property(property: 'user_ids', type: 'array', items: new OAT\Items(
                    type: 'integer',
                )),
            ]),
        ),
        tags: ['parties'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'created party',
                content: new OAT\JsonContent(allOf: [
                    new OAT\Schema(ref: '#/components/schemas/Party'),
                ]),
            ),
        ],
    )]
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'string|max:255',
            'user_ids' => 'array',
            'user_ids.*' => 'numeric|exists:users,id',
        ]);
        $user_ids=$request->get('user_ids');
        $name=$request->get('name');

        $entry = new Party();
        $entry->name = $name;
        $entry->id=$request->get('id');


        foreach ($user_ids as $userId) {
            $entry->users()->attach($userId);
        }
        $entry->save();

        return $entry;

        ##TODO fix post method  (AXIOS post request saves data no problem ?????????????????)


    }

    #[OAT\Delete(
        path: '/api/parties/{id}',
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(properties: [
                new OAT\Property(property: 'id', type: 'int'),
            ]),
        ),
        tags: ['parties'],
        responses: [
            new OAT\Response(response: 204, description: 'deleted party', content: new OAT\JsonContent()),
            new OAT\Response(response: 404, description: 'not found', content: new OAT\JsonContent()),
        ],
    )]
    public function delete(Request $request): JsonResponse
    {
        $id=$request->get('id');
        $party = Party::all()->find($id);
        if($party)
            $party->delete();
        else
            return response()->json([], Response::HTTP_NOT_FOUND);
        return response()->json([], Response::HTTP_NO_CONTENT);




    }

    #[OAT\Put(
        path: '/api/parties/{id}',
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(properties: [
                new OAT\Property(property: 'id', type: 'int'),
                new OAT\Property(property: 'name', type: 'string', maximum: 255),
                new OAT\Property(property: 'user_ids', type: 'array', items: new OAT\Items(
                    type: 'integer',
                )),
            ]),
        ),
        tags: ['parties'],

        responses: [
            new OAT\Response(
                response: 200,
                description: 'updated party',
                content: new OAT\JsonContent(allOf: [
                    new OAT\Schema(ref: '#/components/schemas/Party'),
                ]),
            ),
        ],
    )]
    public function update(Request $request)
    {
        $user_ids=$request->get('user_ids');
        $name=$request->get('name');
        $id=$request->get('id');
        $entry =Party::all()->find($id);
        $entry->name = $name;
        $entry->id=$id;


        foreach ($user_ids as $userId) {
            $entry->users()->attach($userId);
        }
        $entry->save();

        return $entry;


    }
}
