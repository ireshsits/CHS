<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;
use Illuminate\Notifications\Messages\MailMessage;

class NotifyUser extends Notification implements ShouldQueue
{
    use Queueable;

    protected $params;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
    	return ['database', 'broadcast', WebPushChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }
    
    /**
     * Get the database representation
     */
    public function toDatabase($notifiable)
    {
    	return [
    			'subject' => $this->params->subject,
    			'data' => [
    					'complaint_id_pk' => $this->params->data->complaint_id_pk,
    					'reference_number' => $this->params->data->reference_number,
    					'complaint_recipient_branch' => $this->params->data->complaint_recipient_branch,
    					'url' => $this->params->data->url,
    			]
    	];
    }
    
    /**
     * Get the webPush representation
     */
    public function toWebPush($notifiable, $notification)
    {
    	return (new WebPushMessage)
    	->title($this->params->subject.'!')
    	->icon('http://localhost:8000/storage/images/common/sampath-bank-logo.png')
    	->body('Reference Number - '.$this->params->data->reference_number)
    	->action('View Complaint', $this->params->data->url);
    	// ->data(['id' => $notification->id])
    	// ->badge()
    	// ->dir()
     	// ->image()
    	// ->lang()
    	// ->renotify()
    	// ->requireInteraction()
    	// ->tag()
    	// ->vibrate()
    }
    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
    	return new BroadcastMessage([
    			'id' => $this->id,
    			'read_at' => null,
    			'data' => [
    					'subject' => $this->params->subject,
    					'data' => [
    							'complaint_id_pk' => $this->params->data->complaint_id_pk,
    							'reference_number' => $this->params->data->reference_number,
    							'complaint_recipient_branch' => $this->params->data->complaint_recipient_branch,
    							'url' => $this->params->data->url,
    					]
    			],
    	]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
    	return [
    			'id' => $this->id,
    			'read_at' => null,
    			'data' => [
    					'subject' => $this->params->subject,
    					'data' => [
    							'complaint_id_pk' => $this->params->data->complaint_id_pk,
    							'reference_number' => $this->params->data->reference_number,
    							'complaint_recipient_branch' => $this->params->data->complaint_recipient_branch,
    							'url' => $this->params->data->url,
    					]
    			],
    	];
    }
}
