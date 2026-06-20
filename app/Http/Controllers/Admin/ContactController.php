<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * عرض قائمة الرسائل
     */
    public function index(Request $request)
    {
        $query = Contact::query();

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $contacts = $query->latest()->paginate(20);

        return view('admin.contacts.index', compact('contacts'));
    }

    /**
     * عرض تفاصيل الرسالة
     */
    public function show(Contact $contact)
    {
        if ($contact->status === 'new') {
            $contact->update(['status' => 'read']);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * تحديث حالة الرسالة
     */
    public function updateStatus(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,read,replied',
        ]);

        $contact->update($validated);

        return back()->with('success', 'تم تحديث حالة الرسالة بنجاح');
    }

    /**
     * حذف الرسالة
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'تم حذف الرسالة بنجاح');
    }
}




