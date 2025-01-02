<?php

namespace JaredClemence\Imap;

class Folder
{
    private $mailbox;
    private $folderName;

    public function __construct($mailbox, $folderName)
    {
        $this->mailbox = $mailbox;
        $this->folderName = $folderName;
    }

    public function getMessages()
    {
        $emails = imap_search($this->mailbox, 'ALL'); // Fetch all emails
        if ($emails === false) {
            return []; // No emails found
        }

        // Sort emails in ascending order
        sort($emails);

        $messages = [];
        foreach ($emails as $emailNumber) {
            $messages[] = new Message($this->mailbox, $emailNumber);
        }

        return $messages;
    }
}

