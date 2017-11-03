<?php
use Ratchet\ConnectionInterface;

require 'vendor/autoload.php';
require 'config.php';

class BinaryEcho implements \Ratchet\WebSocket\MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, \Ratchet\RFC6455\Messaging\MessageInterface $msg)
    {
        if (!$msg->isBinary()) {
            echo 'The data received is not an image!';
            return;
        }

        if ($filename = $this->uploadImage($msg)) {
            $from->send('http://' . SERVER_IP . '/srv/storage/' . $filename);
        }


        $this->notifyClients($msg, $from);
    }

    private function uploadImage($img)
    {
        $filename = rand() . '.jpg';
        try {
            $result = file_put_contents('srv/storage/' . $filename, $img);
            //message to sender
            echo 'The file ' . $filename . ' was uploaded successfully';
        } catch (Exception $e) {
            echo $e->getMessage();
            return;
        }

        return $filename;
    }

    private function notifyClients($img, $from)
    {
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($img);
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        echo "Conection close";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
    }
}

$wsServer = new Ratchet\WebSocket\WsServer(new BinaryEcho);
$app = new Ratchet\Http\HttpServer($wsServer);

$server = Ratchet\Server\IoServer::factory(
        $app,
        WS_PORT
    );

$server->run();
