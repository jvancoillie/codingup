<?php

namespace App\Utils\Algorithm;

/**
 * @see https://en.wikipedia.org/wiki/Topological_sorting
 */
class TopologicalSorting
{
    /** @var array<string, mixed> */
    protected array $elements = [];
    protected array $visited = [];
    protected array $sorted = [];

    public function __construct(array $elements = [])
    {
        $this->elements = $elements;
    }

    public function add(string $element, array $dependencies = []): static
    {
        if (isset($this->elements[$element])) {
            $this->elements[$element] += $dependencies;
        } else {
            $this->elements[$element] = $dependencies;
        }

        return $this;
    }

    public function sort(): array
    {
        $this->visited = [];
        $this->sorted = [];

        foreach (array_keys($this->elements) as $node) {
            if (!isset($this->visited[$node])) {
                $this->visit($node);
            }
        }

        return array_reverse($this->sorted);
    }

    private function visit($node): void
    {
        $this->visited[$node] = true;

        if (isset($this->elements[$node])) {
            foreach ($this->elements[$node] as $neighbor) {
                if (!isset($this->visited[$neighbor])) {
                    $this->visit($neighbor);
                }
            }
        }

        $this->sorted[] = $node;
    }
}
