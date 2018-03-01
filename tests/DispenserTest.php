<?php

use CashDispenser\Dispenser;
use CashDispenser\NoteUnavailableException;
use PHPUnit\Framework\TestCase;

class DispenserTest extends TestCase
{
    /**
     * @dataProvider invalidDenominations
     * @param array $denominations
     */
    public function testValidation(array $denominations)
    {
        $this->expectException(\InvalidArgumentException::class);
        $dispenser = new Dispenser($denominations);
    }

    /**
     * @param int $amount
     * @param array $denominations
     * @param array $expected
     * @dataProvider defaultAmounts
     */
    public function testDefaultOrderDispense(?int $amount, array $denominations, array $expected)
    {
        $dispenser = new Dispenser($denominations);

        $this->assertEquals($expected, $dispenser->defaultOrderDispense($amount));
    }

    public function testNegativeAmount()
    {
        $dispenser = new Dispenser([1]);

        $this->expectException(\InvalidArgumentException::class);
        $dispenser->defaultOrderDispense(-1);
    }

    public function testUnavailableAmount()
    {
        $dispenser = new Dispenser([100]);

        $this->expectException(NoteUnavailableException::class);
        $dispenser->defaultOrderDispense(80);
    }

    public function defaultAmounts(): array
    {
        return [
            'null' => [
                'amount' => null,
                'denominations' => [1],
                'expected' => [],
            ],
            '#1' => [
                'amount' => 1,
                'denominations' => [1],
                'expected' => [1],
            ],
            '#2' => [
                'amount' => 110,
                'denominations' => [100, 10],
                'expected' => [100, 10],
            ],
            '#3' => [
                'amount' => 120,
                'denominations' => [100, 10],
                'expected' => [100, 10, 10],
            ],
            '#4' => [
                'amount' => 120,
                'denominations' => [100, 20],
                'expected' => [100, 20],
            ],
            'classic' => [
                'amount' => 14856,
                'denominations' => [1000, 500, 100, 50, 5, 1],
                'expected' => [1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,500,100,100,100,50,5,1],
            ],
            '100, 50, 20, 10 #1' => [
                'amount' => 30,
                'denominations' => [100, 50, 20, 10],
                'expected' => [20, 10],
            ],
            '100, 50, 20, 10 #2' => [
                'amount' => 80,
                'denominations' => [100, 50, 20, 10],
                'expected' => [50, 20, 10],
            ],
            'unordered #1' => [
                'amount' => 80,
                'denominations' => [20, 10, 50, 100],
                'expected' => [20, 20, 20, 20],
            ],
            'unordered #2' => [
                'amount' => 90,
                'denominations' => [20, 10, 50, 100],
                'expected' => [20, 20, 20, 20, 10],
            ],
            'unusual set #1' => [
                'amount' => 25,
                'denominations' => [15, 10],
                'expected' => [15, 10],
            ],
            'unusual set #2' => [
                'amount' => 30,
                'denominations' => [15, 10],
                'expected' => [15, 15],
            ],
        ];
    }

    public function invalidDenominations(): array
    {
        return [
            'empty' => [
                [],
            ],
            'float' => [
                [1.00],
            ],
            'non-integer' => [
                ['a string'],
            ],
            'mixed' => [
                [1, 'a string', 0.99],
            ],
        ];
    }

}
