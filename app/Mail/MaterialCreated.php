<?php

namespace App\Mail;

use App\Models\MaterialCategory;
use App\Models\Material;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MaterialCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Material $material,
    )
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Новый материал в категории "'. $this->material->category->name .'" группы "'. $this->material->team->name . '"',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.materials.created',
            with: [
                'teamName' => $this->material->team->name,
                'categoryName' => $this->material->category->name,
                'materialName' => $this->material->name,
                'materialAnnotation' => $this->material->annotation,
                'materialLink' => route('materials.show', $this->material),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
