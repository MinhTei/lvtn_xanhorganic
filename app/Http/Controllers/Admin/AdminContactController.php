<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class AdminContactController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:edit_contacts', only: ['markReplied']),
            new Middleware('permission:delete_contacts', only: ['destroy']),
        ];
    }    public function index(Request $request)
    {
        $query = Contact::latest();

        if ($request->filled('replied')) {
            $query->where('is_replied', $request->replied === '1');
        }

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $contacts = $query->paginate(15)->withQueryString();

        return view('admin.contacts.index', compact('contacts'));
    }

    public function show(Contact $contact)
    {
        return view('admin.contacts.show', compact('contact'));
    }

    public function markReplied(Contact $contact)
    {
        $contact->update(['is_replied' => true]);

        return back()->with('success', 'Đã đánh dấu đã phản hồi.');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Đã xóa liên hệ.');
    }
}
