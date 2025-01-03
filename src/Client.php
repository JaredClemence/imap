<?php

namespace JaredClemence\Imap;

class Client
{
    private $host;
    private $username;
    private $password;
    private $mailbox;

    public function __construct($host, $username, $password)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
    }

    public function connect()
    {
        $this->mailbox = @imap_open($this->host, $this->username, $this->password);

        if (!$this->mailbox) {
            throw new \Exception('IMAP connection failed: ' . imap_last_error());
        }
    }

    public function disconnect()
    {
        if ($this->mailbox) {
            imap_expunge($this->mailbox);
            imap_close($this->mailbox);
        }
    }

    public function getFolder($folderName = 'INBOX')
    {
        $folderName = $folderName ?: 'INBOX';

        // Ensure folder name is in UTF7-IMAP encoding
        $encodedFolderName = imap_utf7_encode($folderName);

        // Reopen to ensure the correct folder
        imap_reopen($this->mailbox, "{$this->host}{$encodedFolderName}");

        return new Folder($this->mailbox, $folderName);
    }
}

