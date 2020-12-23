<?php

class Bag {
    private string $handle;

    private array $bags = [];

    private bool $visited = false;

    public function __construct(string $handle)
    {
        $this->handle = $handle;
    }

    public function add(Bag $bag) {
        $this->bags[$bag->handle] = $bag;
    }


    public function find(string $handle): ?Bag
    {
        if ($handle === $this->handle) {
            return $this;
        }

        if (array_key_exists($handle, $this->bags)) {
            return $this->bags[$handle];
        }

        /** @var Bag $bag */
        foreach ($this->bags as $bag) {
            if (null !== ($temp = $bag->find($handle))) {
                return $temp;
            }
        }

        return null;
    }

    public function getBags(): array
    {
        return $this->bags;
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
}

$rules = @fopen("day7.txt", "r");

$bags = [];

while (!feof($rules)) {
    $buffer = fgets($rules);

    if (feof($rules)) {
        break;
    }

    $currentBatch = explode(" contain ", $buffer);

    $outerBag = new Bag($currentBatch[0]);

    if (0 === count($bags)) {
        $bags[] = $outerBag;
    } else {
        $bagFound = false;

        /** @var Bag $b */
        foreach ($bags as $b) {
            if (null !== ($temp = $b->find($currentBatch[0]))) {
                $outerBag = $temp;
                $bagFound = true;
                break;
            }
        }

        if (!$bagFound) {
            $bags[] = $outerBag;
        }
    }

    $contents = explode(", ", $currentBatch[1]);

    foreach ($contents as $content) {
        $line = explode(" ", $content);

        if ("no" === $line[0]) {
            continue;
        }

        $amount = (int) array_shift($line);

        if (false !== ($pos = strpos($line[count($line) - 1], PHP_EOL))) {
            $line[count($line) - 1] = substr($line[count($line) - 1], 0, -strlen(PHP_EOL) - 1); // Remove return carriage and dot
        }

        $bagName = 1 === $amount
            ? implode(" ", $line)."s"
            : implode(" ", $line);

        $bagFound = false;

        /** @var Bag $bag */
        foreach ($bags as $bag) {
            if (null !== ($temp = $bag->find($bagName))) {
                $outerBag->add($temp);
                $bagFound = true;
                break;
            }
        }

        if (!$bagFound) {
            $outerBag->add(new Bag($bagName));
        }
    }
}

$total = 0;

$stack = [];

/** @var Bag $bag */
foreach ($bags as $bag) {
    if ("shiny gold bags" === $bag->getHandle()) {
        continue;
    }

    $acc = 0;

    array_push($stack, $bag);

    while (0 < count($stack)) {
        /** @var Bag $element */
        $element = array_pop($stack);

        if ("shiny gold bags" === $element->getHandle()) {
            $total += $acc;

            $acc = 0;
            continue;
        }

        if (!$element->isVisited() && "shiny gold bags" !== $element->getHandle()) {
            $element->setVisited(true);

            if ($element->find("shiny gold bags")) {
                ++$acc;
            } else {
                continue;
            }
        }

        /** @var Bag $b */
        foreach ($element->getBags() as $b) {
            $stack[] = $b;
        }
    }
}

echo $total;
