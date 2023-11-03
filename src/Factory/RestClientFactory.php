<?php

declare(strict_types=1);

namespace Brzuchal\RestClient\Factory;

use Brzuchal\RestClient\Attributes\DeleteExchange;
use Brzuchal\RestClient\Attributes\EntityCollectionExchange;
use Brzuchal\RestClient\Attributes\EntityExchange;
use Brzuchal\RestClient\Attributes\GetCollectionExchange;
use Brzuchal\RestClient\Attributes\GetExchange;
use Brzuchal\RestClient\Attributes\PostExchange;
use Brzuchal\RestClient\Attributes\PutExchange;
use Brzuchal\RestClient\RestClientInterface;
use Laminas\Code\Generator\ClassGenerator;
use Laminas\Code\Generator\DocBlock\Tag\GenericTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\FileGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Reflection\ClassReflection;
use Laminas\Code\Reflection\MethodReflection;
use ReflectionAttribute;
use ReflectionException;
use ReflectionMethod;

use function assert;
use function getenv;
use function implode;
use function in_array;
use function str_replace;

/**
 * Generating {@link RestClientInterface} service factory. Service creation
 * generates given interface implementing class and returns a new instance.
 *
 * @experimental
 */
final class RestClientFactory
{
    /**
     * @param class-string<T> $type
     *
     * @return T
     *
     * @throws ReflectionException
     *
     * @template T
     */
    public function create(string $type, RestClientInterface $restClient): object
    {
        $generatedType  = 'Generated\\' . $type;
        $classGenerator = new ClassGenerator($generatedType);
        $classGenerator->setImplementedInterfaces([$type]);
        $classGenerator->addTrait('\\' . RestClientTrait::class);

        $reflectionClass = new ClassReflection($type);

        foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
            $reflectionAttributes = $reflectionMethod->getAttributes(EntityExchange::class, ReflectionAttribute::IS_INSTANCEOF);
            if (empty($reflectionAttributes)) {
                continue;
            }

            $classGenerator->addMethodFromGenerator($this->createMethodGenerator($reflectionMethod, $reflectionAttributes));
        }

        ((int) getenv('DEBUG')) && (new FileGenerator())
            ->setClass($classGenerator)
            ->setFilename(__DIR__ . '/../../' . str_replace('\\', '_', $generatedType) . '.php')
            ->write();
        eval($classGenerator->generate());

        return new $generatedType($restClient);
    }

    /** @param list<ReflectionAttribute> $reflectionAttributes */
    protected function createMethodGenerator(MethodReflection $reflectionMethod, array $reflectionAttributes): MethodGenerator
    {
        $methodGenerator = MethodGenerator::fromReflection($reflectionMethod);
        $methodGenerator->setDocBlock(new DocBlockGenerator(tags: [new GenericTag('inheritdoc')]));
        $info = $reflectionAttributes[0]->newInstance();
        assert($info instanceof EntityExchange);
        $method = match ($info::class) {
            GetExchange::class,
            GetCollectionExchange::class => 'get',
            PostExchange::class => 'post',
            PutExchange::class => 'put',
            DeleteExchange::class => 'delete',
        };
        $parameterMap = [];
        foreach ($reflectionMethod->getParameters() as $parameterReflection) {
            $parameterMap[$parameterReflection->name] = "'{$parameterReflection->name}' => \${$parameterReflection->name}";
        }

        $uriVariablesParameter = empty($parameterMap) ? '' : ', [' . implode(', ', $parameterMap) . ']';
        $entityMethod          = $info instanceof EntityCollectionExchange ? 'toEntityCollection' : 'toEntity';
        $reflectionReturnType  = $reflectionMethod->getReturnType();
        $returnType            = $info instanceof EntityCollectionExchange ? $info->entityType : $reflectionReturnType->getName();
        $returns               = ! in_array($reflectionReturnType->getName(), ['void', 'never'], true);
        $methodGenerator->setBody(
            ($returns ? 'return ' : '') .
            "\$this->restClient->{$method}('{$info->uri}'" . $uriVariablesParameter . ")\n" .
            ($info->acceptableMediaType ? "->accept('{$info->acceptableMediaType}')\n" : '') .
            ($info->acceptableCharset ? "->acceptCharset('{$info->acceptableCharset}')\n" : '') .
            "->retrieve()\n" .
            ($returns ? "->{$entityMethod}(\\{$returnType}::class);\n" : "->is2xxStatus();\n"),
        );
        $methodGenerator->setInterface(false);

        return $methodGenerator;
    }
}
