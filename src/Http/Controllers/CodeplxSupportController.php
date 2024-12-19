<?php

namespace Codeplx\LaravelCodeplxSupport\Http\Controllers;

use Codeplx\LaravelCodeplxSupport\Services\CodeplxSupportAPIService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class CodeplxSupportController extends Controller
{
    /**
     * Display the support page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $categories = (new CodeplxSupportAPIService())->getCategories();

        // Check if the response has an error
        if(isset($categories->error)) {
            return response()->json([$categories], 401);
        }

        return view('codeplx-support::index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store the support request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'category_id' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ],[
            'name.required' => 'Please enter your name',
            'email.required' => 'Please enter your email address',
            'email.email' => 'Please enter a valid email address',
            'category_id.required' => 'Please select a category',
            'subject.required' => 'Please enter a subject',
            'message.required' => 'Please enter a message',
        ]);

        // Check if the validation failed
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        // Send the request
        $response = (new CodeplxSupportAPIService())->sendRequest('POST', 'api/ticket/create', [
            'name' => $request->name,
            'email' => $request->email,
            'category_id' => $request->category_id,
            'priority_id' => 1,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        // Check if the response has an error
        if(isset($response->error)) {
            return redirect()->back()->with('error', $response->error)->withInput();
        }

        // Check if the response is successful
        if ($response->getStatusCode() !== 200) {
            return redirect()->back()->with('error', 'An error occurred while sending your request. Please try again later.')->withInput();
        }

        // Return the response
        $response = json_decode($response->getBody()->getContents());
        return redirect()->back()->with('success', $response->message);
    }
}
