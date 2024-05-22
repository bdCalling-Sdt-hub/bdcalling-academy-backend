<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactusRequest;
use App\Models\Contactus;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function store(ContactusRequest $request)
    {
        $validatedData = $request->validated();

        $contact = new Contactus();
        $contact->fill($validatedData);
        $contact->save();
        if ($contact) {
            return response()->json(['message' => 'Contact created successfully', 'contact' => $contact], 201);
        } else {
            return response()->json(['message' => 'Contact created faile']);
        }
    }

    public function index()
    {
        $contacts = Contactus::orderBy('id', 'desc')->get();
        if ($contacts) {
            return response()->json(['data' => $contacts], 200);
        } else {
            return response()->json(['message' => 'Record not found']);
        }
    }

    public function show($id)
    {
        $contact = Contactus::findOrFail($id);
        if ($contact) {
            return response()->json($contact);
        } else {
            return response()->json(['message' => 'Record not found']);
        }
    }

    public function destroy($id)
    {
        $contact = Contactus::findOrFail($id);
        $contact->delete();
        if ($contact) {
            return response()->json(['message' => 'Contact deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Record not found'], 400);
        }
    }
}
