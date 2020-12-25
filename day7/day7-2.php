<?php

class Edge {
    private Bag $bag;

    private int $amount;

    public function __construct(Bag $bag, int $amount)
    {
        $this->bag = $bag;
        $this->amount = $amount;
    }

    public function getWeight(): int
    {
        return $this->amount;
    }

    public function getBag(): Bag
    {
        return $this->bag;
    }
}

class Bag {
    private string $handle;

    private bool $visited = false;

    private array $edges = [];

    public function __construct(string $handle)
    {
        $this->handle = $handle;
    }

    public function addEdge(Edge $edge): void
    {
        $this->edges[$edge->getBag()->getHandle()] = $edge;
    }

    /**
     * @return Edge[]
     */
    public function getEdges(): array
    {
        return $this->edges;
    }

    public function getHandle(): string
    {
        return $this->handle;
    }

    public function setVisited(bool $visited): void
    {
        $this->visited = $visited;
    }

    public function isVisited(): bool
    {
        return $this->visited;
    }

    public function sum($bags): int
    {
        /** @var Bag|null $bag */
        $bag = null;
        $temp = 0;

        foreach ($this->getEdges() as $edge) {
            if (!array_key_exists($edge->getBag()->getHandle(), $bags)) {
                return 0;
            }

            $bag = $bags[$edge->getBag()->getHandle()];

            $temp += $edge->getWeight() + $edge->getWeight() * $bag->sum($bags);
        }

        return $temp;
    }
}

$rules = @fopen("day7.txt", "r");

$bags = [];

while (!feof($rules)) {
    $buffer = fgets($rules);

    if (feof($rules)) {
        break;
    }

    $currentBatch = explode(" contain ", $buffer);
    $name = explode(" ", $currentBatch[0]);
    array_pop($name);
    $name = implode("-", $name);

    $outerBag = new Bag($name);

    $bags[$name] = $outerBag;

    $contents = explode(", ", $currentBatch[1]);

    foreach ($contents as $content) {
        $line = explode(" ", $content);

        if ("no" === $line[0]) {
            continue;
        }

        $amount = (int) array_shift($line);
        array_pop($line);

        $bagName = implode("-", $line);

        $bag = new Bag($bagName);

        $outerBag->addEdge(new Edge($bag, $amount));
    }
}

$stack = [];

/** @var Bag $bag */
$bag = $bags["shiny-gold"];

$edges = [];

$bagWeight = 0;
$bagTotal = 0;

$bagTotal = $bag->sum($bags);

echo $bagTotal;
