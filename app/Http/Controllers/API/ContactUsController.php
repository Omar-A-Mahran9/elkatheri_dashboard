<?php

namespace App\Http\Controllers\API;

use App\Models\ContactUs;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContactUsResource;
use App\Http\Requests\storeContactUsRequest;
use Illuminate\Support\Facades\Mail;
use App\Models\Employee;


class ContactUsController extends Controller
{
    public function store(storeContactUsRequest $request)
    {
        $contactUsRequest = ContactUs::create($request->validated());

       try {
        // Assuming you want to send to multiple recipients in a loop
        $employees = Employee::get(); // Fetch employees or any other recipient data
        
        Mail::send('mails.contact-us', [
            'reply' => $contactUsRequest->reply,
            'contactUs' => $contactUsRequest,
            'web' => true
        ], function($message) use ($contactUsRequest, $employees) {
            // foreach ($employees as $emp) {
            //     $message->to($emp->email)->subject(__('Contact Us Reply'));
            // }
             $message->to("mail@alkathirimotors.com.sa")->subject(__('New Car Order'));
             $message->to("info@alkathirimotors.com.sa")->subject(__('New Car Order'));
        });

    } catch (\Throwable $th) {
            dd($th->getMessage()) ;
        }  

        return new ContactUsResource($contactUsRequest);
    }
}