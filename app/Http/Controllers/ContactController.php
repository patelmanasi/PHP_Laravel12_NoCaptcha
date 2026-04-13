<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    // 📌 Show contact form
    public function showForm()
    {
        return view('contact');
    }

    // 📌 Store contact form
    public function submitForm(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
            'g-recaptcha-response' => 'required|captcha'
        ]);

        Contact::create($request->only('name','email','message'));

        return redirect('/contact')
            ->with('success', 'Message sent successfully!');
    }

    // 📌 CONTACT DASHBOARD (LIST)
    public function index(Request $request)
    {
        $query = Contact::query();

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('message', 'like', "%{$request->search}%");
        }

        $contacts = $query->orderBy('id', 'asc')->get();

        // ⚠️ IMPORTANT: match your route view name
        return view('contacts.dashboard', compact('contacts'));
    }

    // 📌 TRASH LIST
    public function trash()
    {
        $contacts = Contact::onlyTrashed()->latest()->get();

        return view('contacts.trash', compact('contacts'));
    }

    // 📌 SOFT DELETE (MOVE TO TRASH)
    public function delete($id)
    {
        Contact::findOrFail($id)->delete();

        return redirect('/contact/dashboard')
            ->with('success', 'Contact moved to trash successfully!');
    }

    // 📌 RESTORE FROM TRASH
    public function restore($id)
    {
        Contact::onlyTrashed()->findOrFail($id)->restore();

        return redirect('/contact/trash')
            ->with('success', 'Contact restored successfully!');
    }

    // 📌 PERMANENT DELETE
    public function forceDelete($id)
    {
        Contact::onlyTrashed()->findOrFail($id)->forceDelete();

        return redirect('/contact/trash')
            ->with('success', 'Contact permanently deleted!');
    }
}