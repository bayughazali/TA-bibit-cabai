<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('contact');
    }

   public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'name'    => [
            'required',
            'string',
            'min:10',
            'max:255',
        ],
        'email'   => [
            'required',
            'email',
            'min:8',
            'max:255',
            'regex:/^[a-zA-Z0-9._%+\-]+@gmail\.com$/',
        ],
        'phone'   => [
            'nullable',
            'string',
            'min:11',
            'max:20',
            'regex:/^[0-9]+$/',
        ],
        'subject' => [
            'required',
            'string',
            'min:8',
            'max:255',
        ],
        'message' => [
            'required',
            'string',
            function ($attribute, $value, $fail) {
                $wordCount = str_word_count($value);
                if ($wordCount < 5) {
                    $fail('Pesan harus terdiri dari minimal 5 kata.');
                }
            },
        ],
    ], [
        // Pesan error custom dalam Bahasa Indonesia
        'name.required'    => 'Nama lengkap wajib diisi.',
        'name.min'         => 'Nama lengkap minimal 10 karakter.',

        'email.required'   => 'Email wajib diisi.',
        'email.min'        => 'Email minimal 8 karakter.',
        'email.email'      => 'Format email tidak valid.',
        'email.regex'      => 'Email harus menggunakan @gmail.com.',

        'phone.min'        => 'No. telepon minimal 11 karakter.',
        'phone.regex'      => 'No. telepon hanya boleh berisi angka.',

        'subject.required' => 'Subjek wajib diisi.',
        'subject.min'      => 'Subjek minimal 8 karakter.',

        'message.required' => 'Pesan wajib diisi.',
    ]);

    // Simpan ke database
    Contact::create($validated);

    // Kirim email
    Mail::to('bayualghozali86@gmail.com')->send(new ContactMail($validated));

    return redirect()->back()->with('success', 'Pesan Anda telah terkirim! Kami akan segera merespons.');
}
}