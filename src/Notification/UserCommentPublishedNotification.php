<?php


namespace App\Notification;


use App\Entity\Comment;
use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;

class UserCommentPublishedNotification extends Notification implements EmailNotificationInterface
{
    /**
     * @var Comment
     */
    private $comment;

    public function __construct(Comment $comment)
    {
        parent::__construct('Your post are published.');
        $this->comment = $comment;
    }

    public function asEmailMessage(Recipient $recipient, string $transport = null): ?EmailMessage
    {
        $message = EmailMessage::fromNotification($this, $recipient, $transport);
        $message->getMessage()
            ->htmlTemplate('emails/comment_published_notification.html.twig')
            ->context(['comment' => $this->comment]);

        return $message;
    }
}