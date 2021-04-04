<?php

namespace App\Info;

use App\Enum\AdminOperationEnum;
use http\Exception\InvalidArgumentException;

class Logger implements LoggerInterface
{
    private string $projectDir;

    public function __construct()
    {
        $this->projectDir = __DIR__ . '/../../public';
    }

    public function info(string $content): void
    {
        $time = new \DateTime();
        if (file_exists($log = $this->projectDir . '/log.txt')) {
            file_put_contents($log, "\n" . $time->format('Y-m-d H-i-s') . ': ' . $content, FILE_APPEND);
        } else {
            $log = fopen($this->projectDir . '/log.txt', 'a');
            fwrite($log, $time->format('Y-m-d H-i-s') . ': ' . $content);
            fclose($log);
        }
    }

    public function logBuilderOperation(object $object): void
    {
        $string = 'Installed: ' . $object->printCharacteristics();

        $this->info($string);
    }

    public function logAdminOperation($event)
    {
        switch ($event) {
            case AdminOperationEnum::ORDER_RECEIVED:
                $this->info('Order received from client');
                break;
            case AdminOperationEnum::ORDER_IN_PROCESS:
                $this->info('Order received by manager');
                break;
            case AdminOperationEnum::ORDER_COMPLETED:
                $this->info('Order completed');
                break;
            default:
                throw new InvalidArgumentException('$event has incorrect value');
        }
    }
}