<?php declare(strict_types = 1);

namespace App\Tests\integration\Command;

use App\Command\AwsInitCommand;
use App\Kernel;
use Aws\Exception\AwsException;
use Aws\Result;
use Aws\Sqs\SqsClient;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class AwsInitCommandTest extends KernelTestCase
{
    private const FOO_QUEUE = "FOO_QUEUE";

    /** @var array */
    private $envVars;

    /** @var SqsClient */
    private $sqsClient;

    protected function setUp()
    {
        $this->sqsClient = $this->prophesize(SqsClient::class);

        $this->envVars = \getenv();
        $this->purgeEnv();

        \putenv('FOO_SQS_QUEUE=' . self::FOO_QUEUE);
    }

    protected function purgeEnv(): void
    {
        foreach (\getenv() as $key => $value) {
            \putenv($key);
        }
    }

    public function testExecuteProdEnv(): void
    {
        $tester = $this->getCommandTester('prod');
        $tester->execute(['command' => 'app:aws-init']);

        $display = $tester->getDisplay();

        $this->assertEquals(1, $tester->getStatusCode());
        $this->assertContains('Command can be run in DEV environment only', $display);
    }

    private function getCommandTester(string $kernelEnv = 'dev'): CommandTester
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = new AwsInitCommand($this->sqsClient->reveal(), $kernelEnv);
        $command->setApplication($application);

        return new CommandTester($command);
    }

    public function testExecute(): void
    {
        $this->sqsClient->createQueue(['QueueName' => self::FOO_QUEUE])->shouldBeCalled()->willReturn(
            new Result(['QueueUrl' => 'http://localstack:4576/queue/' . self::FOO_QUEUE])
        );
        $this->sqsClient->purgeQueue(['QueueUrl' => 'http://localstack:4576/queue/' . self::FOO_QUEUE])
                        ->shouldBeCalled();

        $tester = $this->getCommandTester();
        $tester->execute(['command' => 'app:aws-init']);

        $display = $tester->getDisplay();

        $this->assertEquals(0, $tester->getStatusCode());
        $this->assertContains('Infrastructure created', $display);
        $this->assertContains(self::FOO_QUEUE . ' queue created', $display);
    }

    public function testExecutePossibleExceptions(): void
    {
        $this->sqsClient->createQueue(Argument::type("array"))->willThrow(AwsException::class);

        $tester = $this->getCommandTester();
        $tester->execute(['command' => 'app:aws-init']);

        $display = $tester->getDisplay();
        $this->assertNotContains(self::FOO_QUEUE . ' queue created', $display);
        $this->assertContains('Finished with errors', $display);
    }

    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }

    protected function tearDown()
    {
        $this->purgeEnv();

        foreach ($this->envVars as $key => $value) {
            \putenv($key . '=' . $value);
        }

        parent::tearDown();
    }
}
