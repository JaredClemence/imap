<?php

namespace JaredClemence\Imap;

class ImapClient
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

        return $this;
    }

    public function getFolders()
    {
        $folders = imap_list($this->mailbox, $this->host, '*');
        if ($folders === false) {
            throw new \Exception('Failed to fetch folders: ' . imap_last_error());
        }

        return array_map('imap_utf7_decode', $folders);
    }

    public function getMessages($folder)
    {
        imap_reopen($this->mailbox, $folder);

        $emails = imap_search($this->mailbox, 'ALL');
        if ($emails === false) {
            return [];
        }

        $messages = [];
        foreach ($emails as $emailNumber) {
            $header = imap_headerinfo($this->mailbox, $emailNumber);
            $body = imap_fetchbody($this->mailbox, $emailNumber, 1);
            $messages[] = [
                'subject' => $header->subject ?? '',
                'from' => $header->fromaddress ?? '',
                'body' => $body,
		'header'=> $header,
            ];
        }

        return $messages;
    }

    public function close()
    {
        if ($this->mailbox) {
            imap_close($this->mailbox);
        }
    }
}

