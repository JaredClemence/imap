<?php

namespace JaredClemence\Imap;

class Message
{
    private $mailbox;
    private $emailNumber;
    private $header;
    private $body;

    public function __construct($mailbox, $emailNumber)
    {
        $this->mailbox = $mailbox;
	$this->emailNumber = $emailNumber;
	$this->header = null;
	$this->body = null;
    }
    
    public function markForDeletion(){
        \imap_delete($this->mailbox, $this>emailNumber);
    }
    
    public function markRead(){
        \imap_setflag_full($this->mailbox, $this->emailNumber, "\\Seen");
    }

    public function getHeader(){
	if($this->header==null) $this->header = \imap_headerinfo($this->mailbox, $this->emailNumber);
	return $this->header;
    }

    public function getSubject()
    {
        return $this->getHeader()->subject ?? '';
    }

    public function getFrom()
    {
        return $this->getHeader()->fromaddress ?? '';
    }

    public function getBody()
    {
	    if($this->body == null){ 
                $this->body = imap_fetchbody($this->mailbox, $this->emailNumber, 1); 
                // Plain text body                                             
	    }
            return $this->body;
    }
}

