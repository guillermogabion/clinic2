<?php

namespace App\Http\Controllers;
use Twilio\Rest\Client;
use App\Category;
use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;

class CategoryController extends Controller
{
    //

    public function store(Request $request)
    {

        // return $request;
        $category = new Category();
        $category->name = $request->name;
        if ($request->avatar) {
            $image = $request->avatar;  // your base64 encoded
            list($type, $image) = explode(';', $image);
            list(, $image)      = explode(',', $image);
            $data = base64_decode($image);
            $imageName = date("YmdHis") . '.' . 'jpeg';
            file_put_contents(public_path() . '/' . 'images/category/' . $imageName, $data);
            $category->avatar = $imageName;
        }
        $category->save();
        // $requestData = $request->all();
        // $data->create($requestData);
        return response()->json([
            'message' => "Category Added"
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $data = Category::find($id);
        $requestData = $request->all();
        $data->update($requestData);
        return response()->json([
            'message' => 'Successfully Updated'
        ], 200);
    }

    public function index()
    {
        return Category::get();
    }

    public function sendSms()
    {
        $apiKey = 'PR-AMALI551607_QIP8K';
        $url = 'https://api.itexmo.com/api/broadcast-2d';

        $client = new GuzzleClient();

        $response = $client->request('POST', $url, [
            'form_params' => [
                'Email' => 'amaliagraceabarrientos@gmail.com',
                'Password' => 'January182020',
                'ApiCode' => $apiKey,
                'Recipients' => '09484996063',
                'Message' => "Hii. Hello",
                'SenderId'=> "ITEXMO SMS"
            ]
        ]);

        $statusCode = $response->getStatusCode();
        $responseBody = json_decode($response->getBody(), true);

        if ($statusCode == 200 && $responseBody['response_code'] == 0) {
            // Message sent successfully
            return true;
        } else {
            // Error sending message
            return false;
        }
            // $myVar = 'Hello, world!';
    // dump($myVar);
    }
    
}
