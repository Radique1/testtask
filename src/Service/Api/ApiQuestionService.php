<?php

declare(strict_types = 1);

namespace App\Service\Api;

use App\Dto\ApiDto;
use App\Dto\ApiOperationDto;
use App\Dto\ApiResourceDto;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\HttpFoundation\Request;

class ApiQuestionService
{
    private readonly InputInterface $input;

    private readonly OutputInterface $output;

    /** @var QuestionHelper $helper */
    private readonly HelperInterface $helper;

    public function init(
        InputInterface $input,
        OutputInterface $output,
        HelperInterface $helper
    ): void
    {
        $this->input = $input;
        $this->output = $output;
        $this->helper = $helper;
    }

    public function askForResource(ApiDto $apiDto): ?string
    {
        $options = array_keys($apiDto->getResources());

        $question = new ChoiceQuestion(
            'Choose a resource',
            array_merge($options, ['Quit']),
        );

        $question->setErrorMessage('Option %s is not valid.');
        $answer = $this->helper->ask($this->input, $this->output, $question);

        return in_array($answer, $options) ? $answer : null;
    }

    public function askForAction(ApiResourceDto $resource): ?string
    {
        $options = array_keys($resource->getOperations());

        $question = new ChoiceQuestion(
            'Choose an operation',
            array_merge($options, ['Back to resources']),
        );
        $question->setErrorMessage('Option %s is not valid.');
        $answer = $this->helper->ask($this->input, $this->output, $question);

        return in_array($answer, $options) ? $answer : null;
    }

    public function askForPathParameters(array $pathParameters): array
    {
        $result = [];
        $format = 'Provide %s: ';

        foreach ($pathParameters as $pathParameter) {
            $question = new Question(sprintf($format, $pathParameter));

            $result[$pathParameter] = $this->helper->ask($this->input, $this->output, $question);
        }

        return $result;
    }

    public function askForPayload(ApiOperationDto $operation): array
    {
        $result = [];
        $format = 'Provide %s%s: ';
        $additionalIno = $operation->getMethod() === Request::METHOD_PATCH
            ? '(leave empty if shouldn\'t be updated)'
            : '';

        foreach ($operation->getFields() as $field) {
            $question = new Question(sprintf($format, $field, $additionalIno));

            $answer = $this->helper->ask($this->input, $this->output, $question);
            if ($answer) {
                $result[$field] = $answer;
            }
        }

        return $result;
    }
}