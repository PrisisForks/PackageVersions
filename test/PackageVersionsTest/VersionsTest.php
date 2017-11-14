<?php

namespace PackageVersionsTest;

use PackageVersions\Versions;
use PHPUnit_Framework_TestCase;

/**
 * @covers \PackageVersions\Versions
 */
final class VersionsTest extends PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        parent::setUp();

        \PHPUnit_Framework_Error_Deprecated::$enabled = FALSE;
    }

    public function testValidVersions()
    {
        $lockData = json_decode(file_get_contents(__DIR__ . '/../../composer.lock'), true);

        $packages = array_merge($lockData['packages'], $lockData['packages-dev']);

        self::assertNotEmpty($packages);

        foreach ($packages as $package) {
            self::assertSame(
                $package['version'] . '@' . $package['source']['reference'],
                Versions::getVersion($package['name'])
            );
        }
    }

    public function testInvalidVersionsAreRejected()
    {
        $this->setExpectedException(\OutOfBoundsException::class);

        Versions::getVersion(uniqid('', true) . '/' . uniqid('', true));
    }

    public function testGetShortVersion()
    {
        $lockData = json_decode(file_get_contents(__DIR__ . '/../../composer.lock'), true);

        $packages = array_merge($lockData['packages'], $lockData['packages-dev']);

        self::assertNotEmpty($packages);

        foreach ($packages as $package) {
            self::assertSame(
                $package['version'],
                Versions::getShortVersion($package['name'])
            );
        }
    }

    public function testInvalidVersionsAreRejectedInGetShortVersion()
    {
        $this->setExpectedException(\OutOfBoundsException::class);

        Versions::getShortVersion(uniqid('', true) . '/' . uniqid('', true));
    }

    public function testGetMajorVersion()
    {
        $lockData = json_decode(file_get_contents(__DIR__ . '/../../composer.lock'), true);

        $packages = array_merge($lockData['packages'], $lockData['packages-dev']);

        self::assertNotEmpty($packages);

        foreach ($packages as $package) {
            self::assertSame(
                explode('.', $package['version'])[0],
                Versions::getMajorVersion($package['name'])
            );
        }
    }

    public function testInvalidVersionsAreRejectedInGetMajorVersion()
    {
        $this->setExpectedException(\OutOfBoundsException::class);

        Versions::getMajorVersion(uniqid('', true) . '/' . uniqid('', true));
    }
}
