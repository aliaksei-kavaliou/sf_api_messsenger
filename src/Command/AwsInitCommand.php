<?php declare(strict_types = 1);

namespace App\Command;

use Aws\Exception\AwsException;
use Aws\Sqs\SqsClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AwsInitCommand extends Command
{
    protected static $defaultName = 'app:aws-init';

    /** @var SqsClient */
    private $sqsClient;

    /** @var string  */
    private $kernelEnvironment;

    /**
     * AwsInitCommand constructor.
     *
     * @param SqsClient $sqsClient
     * @param string    $kernelEnvironment
     */
    public function __construct(SqsClient $sqsClient, string $kernelEnvironment)
    {
        $this->sqsClient = $sqsClient;
        $this->kernelEnvironment = $kernelEnvironment;

        parent::__construct();
    }


    protected function configure()
    {
        $this->setDescription('init aws sqs infrastructures');
    }


    /** @inheritdoc */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        if ('dev' !== $this->kernelEnvironment) {
            $io->error('Command can be run in DEV environment only');

            return 1;
        }

        $withErrors = false;

        foreach (\getenv() as $key => $value) {
            if (false !== \strpos($key, 'SQS_QUEUE')) {
                $withErrors |= !$this->createQueue($value, $io);

                continue;
            }

        }

        if ($withErrors) {
            $io->note('Finished with errors.');
        }

        $io->text("Infrastructure created. Bye!\n");

        return 0;
    }

    /**
     * @param string       $value
     * @param SymfonyStyle $io
     *
     * @return bool
     */
    private function createQueue(string $value, SymfonyStyle $io): bool
    {
        try {
            $result = $this->sqsClient->createQueue(['QueueName' => $value]);
            $this->sqsClient->purgeQueue(['QueueUrl' => $result->get('QueueUrl')]);
            $io->success($value . ' queue created.');
            $io->success('QueueUrl: ' . $result->get('QueueUrl'));
        } catch (AwsException $e) {
            $io->error($e->getAwsErrorMessage());

            return false;
        }

        return true;
    }
}
