<?php

namespace CashDispenser;

class Dispenser
{
    /**
     * @var int[]
     */
    private $denominations;

    /**
     * Note that the order of denominations is important:
     * 100, 10 and 10, 100 will give different result when
     * using Dispenser::defaultOrderDispense()
     *
     * @param int[] $denominations
     */
    public function __construct(array $denominations)
    {
        $this->validateDenominations($denominations);

        $this->denominations = array_unique($denominations);
    }

    /**
     * @param int $amount
     * @return int[]
     * @throws NoteUnavailableException
     * @throws \InvalidArgumentException
     */
    public function defaultOrderDispense(?int $amount): array
    {
        if ($amount === null) {
            return [];
        }

        if ($amount < 0) {
            throw new \InvalidArgumentException('Amount must be positive integer');
        }

        $notes = $this->countNotes($amount, $this->denominations);
        $dispense = $this->convertNotesToResult($notes);

        if (array_sum($dispense) !== $amount) {
            throw new NoteUnavailableException('Given amount cannot be dispensed with given denominations');
        }

        return $dispense;
    }

    /**
     * @param int $amount
     * @param int[] $denominations
     * @return array
     */
    private function countNotes(int $amount, array $denominations): array
    {
        $notes = array_fill_keys($denominations, 0);
        $total = $amount;

        foreach ($this->denominations as $denomination) {
            if ($total <= 0) {
                break;
            }
            $count = (int)floor($total / $denomination);
            $notes[$denomination] = $count;
            $total -= $count * $denomination;
        }

        return $notes;
    }

    /**
     * @param array $notes
     * @return int[]
     */
    private function convertNotesToResult(array $notes): array
    {
        $dispense = [];
        foreach ($notes as $denomination => $count) {
            $dispense = array_merge($dispense, array_fill(0, $count, $denomination));
        }

        return $dispense;
    }


    /**
     * @param array $denominations
     * @throws \InvalidArgumentException
     */
    private function validateDenominations(array $denominations): void
    {
        if (empty($denominations)) {
            throw new \InvalidArgumentException('Empty denominations');
        }

        array_map(function ($denomination): int {
            if (!is_int($denomination)) {
                throw new \InvalidArgumentException('Denominations must be integers');
            }

            return $denomination;
        }, $denominations);
    }
}
