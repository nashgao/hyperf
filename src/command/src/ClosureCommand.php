<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Hyperf\Command;

use Closure;
use Psr\Container\ContainerInterface;

final class ClosureCommand extends Command
{
    private ParameterParser $parameterParser;

    public function __construct(
        private ContainerInterface $container,
        string $signature,
        private Closure $closure
    ) {
        $this->signature = $signature;
        $this->parameterParser = $container->get(ParameterParser::class);

        parent::__construct();
    }

    public function handle()
    {
        $inputs = array_merge($this->input->getArguments(), $this->input->getOptions());
        $parameters = $this->parameterParser->parseClosureParameters($this->closure, $inputs);

        return $this->closure->call($this, ...$parameters);
    }

    public function describe(string $description): self
    {
        $this->setDescription($description);

        return $this;
    }
}
