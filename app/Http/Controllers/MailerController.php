<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\Mailer;

class MailerController extends Controller
{
    public function sendMail(Request $request): \Illuminate\Http\JsonResponse
    {
        $to = $request->input('to');
        $subject = $request->input('subject');
        $body = $request->input('body');

        if (!Mailer::sendMail($to, $subject, $body)) {
            return response()->json(['message' => 'Failed to send email.'], 500);
        }
        return response()->json(['message' => 'Email sent successfully.']);
    }
}
