<?php

declare(strict_types = 1);

namespace App\Command;

use App\Service\Api\ApiService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'api:interface', description: 'API interface')]
class ApiCommand extends Command
{
    public function __construct(
        private readonly ApiService $apiService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->apiService->access($input, $output, $this->getHelper('question'));

        return Command::SUCCESS;
    }
}