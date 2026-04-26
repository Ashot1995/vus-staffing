<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewJobApplicationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Application $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function envelope(): Envelope
    {
        $jobTitle = $this->application->is_spontaneous
            ? 'Spontaneous Application'
            : ($this->application->job?->title ?? 'Job Application');

        return new Envelope(
            subject: 'New Job Application: ' . $jobTitle,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-job-application',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
