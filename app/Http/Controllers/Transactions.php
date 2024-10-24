<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use GuzzleHttp\Client;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Testing\Fakes\Fake;

class Transactions extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function list()
    {
        return Transaction::query()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $client = new Client([
            'base_uri' => 'numerator-api:3000/',
            'timeout'  => 10.0,
        ]);

        $client->post('numerator-api:3000/numerator/lock');

        $response = $client->request('GET', 'numerator');
        $numerator = json_decode($response->getBody())->numerator;
        $newValue = intval(Str::uuid()->toString());

        try {
            $client->put(
                'numerator-api:3000/numerator/test-and-set',
                [
                    'json' => [
                        'oldValue' => $numerator,
                        'newValue' => $newValue,
                    ]
                ]
            );
        } catch (\Exception $ex) {
            Log::error("Unable to set a new value");
            return new JsonResponse(['message' => 'Unable to create a transaction, please try again later.'], 409);
        }
        
        
        $expirationDate = new \DateTime($request->get('cardExpirationDate'));

        Transaction::create([
            'id' => $newValue,
            'value' => $request->get('value'),
            'description' => $request->get('description'),
            'method' => $request->get('method'),
            'cardNumber' => $request->get('cardNumber'),
            'cardHolderName' => $request->get('cardHolderName'),
            'cardExpirationDate' => $expirationDate,
            'cardCvv' => $request->get('cardCvv'),
        ]);

        $client->delete('numerator-api:3000/numerator/lock');

        return new JsonResponse(['success' => 'ok'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = Transaction::query()->where(['id' => $id])->get()->first();
        if ($transaction) {
            return $transaction;
        }

        return new JsonResponse(['message' => 'Not Found.'], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
