<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Config;

class SendMail extends Mailable {
	use Queueable, SerializesModels;
	
	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($params) {
		$this->params = $params;
		$this->setHeader ();
	}
	
	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
		// if the bcc is set
		if (isset ( $this->params->cc )) {
			return $this->to ( $this->params->to )
			->cc ( $this->params->cc )
			->from ( $this->params->from, Config::get ( 'mail.from.name' ) )
			->subject ( $this->params->subject )
			->view ( $this->params->template )->with ( 'data', $this->params->data );
		}
		return $this->to ( $this->params->to )
		->from ( $this->params->from, Config::get ( 'mail.from.name' ) )
		->subject ( $this->params->subject )
		->view ( $this->params->template )->with ( 'data', $this->params->data );
	}
	public function setHeader() {
		$this->withSwiftMessage ( function ($message) {
			if ($this->params->header->type) {
				$message->getHeaders ()->addTextHeader ( 'Mail-Type', $this->params->header->type );
			}
			
			if ($this->params->header->name) {
				$message->getHeaders ()->addTextHeader ( 'Sender-Name', $this->params->header->name );
			}
		} );
	}
}
