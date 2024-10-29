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
      // Format the Saudi phone number
      $formattedPhone = formatSaudiPhoneNumber($request->phone);

      // Get the validated data from the request
      $validatedData = $request->validated();
  
      // Replace the 'phone' field with the formatted phone number
      $validatedData['phone'] = $formattedPhone;
  
      // Create a new ContactUs record using the modified validated data
      $contactUsRequest = ContactUs::create($validatedData);
  

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
             $message->to(settings()->get('email'))->subject(__('New contact'));
      
        });

    } catch (\Throwable $th) {
            dd($th->getMessage()) ;
        }  

        return new ContactUsResource($contactUsRequest);
    }
}
