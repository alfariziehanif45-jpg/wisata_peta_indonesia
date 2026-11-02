<?php

namespace App\Http\Controllers;

use App\Mail\FeedbackMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'type'    => ['required', 'in:bug,saran,lainnya'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ], [
            'type.in'        => 'Jenis laporan tidak valid.',
            'subject.required' => 'Subjek wajib diisi.',
            'message.required' => 'Pesan wajib diisi.',
            'message.min'    => 'Pesan minimal 10 karakter.',
        ]);

        $user = Auth::user();

        $payload = [
            'type'    => $data['type'],
            'subject' => $data['subject'],
            'message' => $data['message'],
            'user'    => [
                'id'       => $user->id,
                'name'     => $user->name,
                'username' => $user->username,
                'email'    => $user->email,
            ],
            'sent_at' => now()->translatedFormat('d F Y H:i'),
            'ip'      => $request->ip(),
            'agent'   => substr((string) $request->userAgent(), 0, 300),
        ];

        // Kirim ke email tujuan
        Mail::to('zaykayla575@gmail.com')->send(new FeedbackMail($payload));

        return back()->with(
            'feedback_success',
            '✅ Terima kasih! Laporan/saranmu sudah terkirim ke tim kami. Kami akan segera meninjaunya.'
        );
    }
}