<?php

namespace BretRZaun\StatusPage\Tests\Check;

use BretRZaun\StatusPage\Check\PhpExtensionCheck;
use PHPUnit\Framework\TestCase;

class PhpExtensionCheckTest extends TestCase
{
    public function getTestPhpExtensionCheck(): array
    {
        return [
            [null, null, '7.1.0', true],
            ['7.0.0', '7.2.0', '7.1.0', true],
            ['7.0.0', '7.2.0', '7.0.0', true],
            ['7.0.0', '7.2.0', '7.2.0', false],
            ['7.0.0', '7.2.0', '5.6.0', false],
            ['7.0.0', '7.2.0', '7.3.0', false],
            [null, '7.2.0', '5.6.0', true],
            [null, '7.2.0', '7.1.0', true],
            [null, '7.2.0', '7.3.0', false],
            ['7.0.0', null, '5.6.0', false],
            ['7.0.0', null, '7.1.0', true],
            ['7.0.0', null, '7.3.0', true],
        ];
    }

    /**
     * @param $greaterEquals
     * @param $lessThan
     * @param $extensionVersion
     * @param $expected
     *
     * @dataProvider getTestPhpExtensionCheck
     */
    public function testPhpExtensionCheck($greaterEquals, $lessThan, $extensionVersion, $expected): void
    {
        $mock = $this->getMockBuilder(PhpExtensionCheck::class)
            ->setConstructorArgs(['Test', 'test_extension', $greaterEquals, $lessThan])
            ->setMethods(['isExtensionLoaded', 'getExtensionVersion'])
            ->getMock();
        $mock->expects($this->once())
            ->method('isExtensionLoaded')
            ->willReturn(true);
        $mock->expects($this->once())
            ->method('getExtensionVersion')
            ->willReturn($extensionVersion);

        /** @var PhpExtensionCheck $mock */
        $result = $mock->checkStatus();
        $actual = $result->getSuccess();

        $this->assertEquals($expected, $actual);
    }
}
