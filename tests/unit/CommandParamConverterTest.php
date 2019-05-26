<?php declare(strict_types = 1);

namespace App\Tests\unit;

use App\Service\CommandParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

class CommandParamConverterTest extends KernelTestCase
{
    public function testApply(): void
    {
        $body = [
            'bar' => ['abc']
        ];

        $request = new Request(
            [
                'quxString' => 'xyz',
                'quux' => 'corge',
            ],
            [
                'bar' => '123',
            ],
            [
                'baz' => 123
            ],
            [],
            [],
            [],
            \json_encode($body)
        );

        $values = [
            'name' => 'command',
            'class' => '\App\Tests\unit\Foo',
            'converter' => 'command_param_converter',
            'options' => ['map' => ['quxString' => 'qux', 'extraValue' => 'notExpected']],
        ];

        $paramConverter = new CommandParamConverter();
        $result = $paramConverter->apply($request, new ParamConverter($values));
        $this->assertTrue($result);
        $command = $request->attributes->get("command");
        $this->assertInstanceOf(Foo::class, $command);
        $this->assertEquals(['abc'], $command->getBar());
        $this->assertInternalType('int', $command->getBaz());
        $this->assertEquals(123, $command->getBaz());
        $this->assertEquals('xyz', $command->getQux());
    }
}

/**
 * @internal
 */
class Foo
{
    private $bar;
    private $baz;
    private $qux;

    /**
     * Foo constructor.
     *
     * @param array  $bar
     * @param int    $baz
     * @param string $qux
     */
    public function __construct(array $bar, int $baz, string $qux)
    {
        $this->bar = $bar;
        $this->baz = $baz;
        $this->qux = $qux;
    }

    /**
     * @return mixed
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @return mixed
     */
    public function getBaz()
    {
        return $this->baz;
    }

    /**
     * @return mixed
     */
    public function getQux()
    {
        return $this->qux;
    }
}
