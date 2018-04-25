<?php 
namespace AppBundle\Util;

use Swift_Events_SendListener;

class MessageFileLogger implements Swift_Events_SendListener
{
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function getMessages()
    {
        return $this->read();
    }

    public function clear()
    {
        $this->write(array());
    }

    public function beforeSendPerformed(Swift_Events_SendEvent $evt)
    {
        $messages = $this->read();
        $messages[] = clone $evt->getMessage();

        $this->write($messages);
    }

    public function sendPerformed(Swift_Events_SendEvent $evt)
    {
    }

    private function read()
    {
        if (!file_exists($this->filename)) {
            return array();
        }

        return (array) unserialize(file_get_contents($this->filename));
    }

    private function write(array $messages)
    {
        file_put_contents($this->filename, serialize($messages));
    }
}