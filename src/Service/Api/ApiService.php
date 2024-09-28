<?php

declare(strict_types = 1);

namespace App\Service\Api;

use App\Dto\ApiDto;
use App\Dto\ApiServiceStateDto;
use App\Service\Converter\PayloadConverter;
use App\Service\Http\HttpClientService;
use http\Exception\UnexpectedValueException;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\Exception\ClientException;

class ApiService
{
    public const API_RESOURCE_NAMESPACE = 'App\Entity\\';

    private ApiServiceStateDto $state;

    private ApiDto $apiDto;

    private ApiQuestionService $questionService;

    public function __construct(
        private readonly ApiExtractor $extractor,
        private readonly HttpClientService $httpClient,
        private readonly PayloadConverter $payloadConverter,
        private readonly ApiResponseService $responseService,
    ) {
        $this->state = new ApiServiceStateDto();
        $this->apiDto = $this->extractor->extract();
        $this->questionService = new ApiQuestionService();
    }

    public function access(InputInterface $input, OutputInterface $output, HelperInterface $helper): void
    {
        $this->questionService->init($input, $output, $helper);

        while (true) {
            if (!$this->processResource()) {
                break;
            }

            if (!$this->processAction()) {
                continue;
            }

            $this->processPathParameters();

            $entityClassName = self::API_RESOURCE_NAMESPACE . $this->state->getResource();

            $this->processPayload($entityClassName);

            try {
                $response = $this->httpClient->request($this->state);
            } catch (ClientException $exception) {
                $output->writeln('<error>' . $exception->getMessage() . '</error>');
                $this->clean();
                continue;
            }

            if ($response) {
                $this->responseService->outputResponse($response, $entityClassName, $output);
            }


            $this->clean();
        }
    }

    private function processResource(): bool
    {
        if (!$this->state->getResource()) {
            $resource = $this->questionService->askForResource($this->apiDto);

            if (!$resource) {
                return false;
            }

            $this->state->setResource($resource);
        }

        return true;
    }

    private function processAction(): bool
    {
        if (!$this->state->getAction()) {
            $resource = $this->apiDto->getResource($this->state->getResource());

            if (!$resource) {
                throw new UnexpectedValueException('Resource is not found.');
            }

            $action = $this->questionService->askForAction($resource);

            if (!$action) {
                $this->state->setResource(null);

                return false;
            }

            $this->state->setAction($action);
            $this->state->setRouteName($resource->getOperation($action)->getRouteName());
            $this->state->setMethod($resource->getOperation($action)->getMethod());
        }

        return true;
    }

    private function processPathParameters(): void
    {
        if ($this->state->getPathParameters() === null) {
            $operation = $this->apiDto
                ->getResource($this->state->getResource())
                ->getOperation($this->state->getAction());

            if (!$operation->getPathParameters()) {
                $this->state->setPathParameters([]);
            }

            $this->state->setPathParameters(
                $this->questionService->askForPathParameters($operation->getPathParameters())
            );
        }
    }

    private function processPayload(string $entityClassName): void
    {
        if ($this->state->getPayload() === null) {
            $operation = $this->apiDto
                ->getResource($this->state->getResource())
                ->getOperation($this->state->getAction());

            if (!$operation->getFields()) {
                $this->state->setPayload([]);
            }

            $this->state->setPayload(
                $this->payloadConverter->convert(
                    $this->questionService->askForPayload($operation),
                    $entityClassName,
                )
            );
        }
    }

    private function clean(): void
    {
        $this->state->setPayload(null);
        $this->state->setPathParameters(null);
        $this->state->setAction(null);
    }
}