<?php declare(strict_types = 1);

namespace App\Service;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class CommandParamConverter implements ParamConverterInterface
{
    private const NAME = 'command_param_converter';

    /**
     * Stores the object in the request.
     *
     * @param Request        $request
     * @param ParamConverter $configuration Contains the name, class and options of the object
     *
     * @return bool True if the object has been successfully set, else false
     * @throws \ReflectionException
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $class = $configuration->getClass();
        $name = $configuration->getName();
        $options = (array)$configuration->getOptions();
        $reflectionClass = new \ReflectionClass($class);
        $command = $reflectionClass->newInstanceWithoutConstructor();
        $constructor = $reflectionClass->getConstructor();
        if (!$constructor) {
            return true;
        }

        $constructorParameters = $constructor->getParameters();

        $input = [];

        if ($body = $request->getContent()) {
            $input = \json_decode($body, true);
        }

        foreach ($request->query as $item => $value) {
            $input[$item] = $value;
        }

        foreach ($request->attributes as $key => $value) {
            $input[$key] = $value;
        }

        foreach ($options['map'] ?? [] as $given => $expected) {
            $input[$expected] = $input[$given] ?? null;
            unset($input[$given]);
        }

        foreach ($constructorParameters as $parameter) {
            $property = $reflectionClass->getProperty($parameter->getName());
            $property->setAccessible(true);
            try {
                $defaultValue = $parameter->getDefaultValue();
            } catch (\ReflectionException $e) {
                $defaultValue = null;
            }

            $attribute = $input[$property->getName()] ?? $defaultValue;
            $type = $parameter->getType();

            if (null === $attribute) {
                $property->setValue($command, null);

                continue;
            }

            if (!empty($options['cast'][$property->getName()])) {
                \settype($attribute, $options['cast'][$property->getName()]);
            } elseif ($type->isBuiltin()) {
                \settype($attribute, $type->getName());
            }

            $property->setValue($command, $attribute ?? $defaultValue);
        }

        $request->attributes->set($name, $command);

        return true;
    }

    /**
     * Checks if the object is supported.
     *
     * @return bool True if the object is supported, else false
     */
    public function supports(ParamConverter $configuration): bool
    {
        return null !== $configuration->getClass() && self::NAME === $configuration->getConverter();
    }
}
