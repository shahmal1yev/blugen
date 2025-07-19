<?php

namespace Blugen\Tests\Unit\Service\Lexicon\V1;

use Blugen\Config\ConfigManager;
use Blugen\Service\Lexicon\V1\Reader;
use Blugen\Tests\TestCase;
use RuntimeException;

class ReaderTest extends TestCase
{
    private string $tempDir;
    private string $tempFile;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tempDir = sys_get_temp_dir() . '/reader_test_dir_' . uniqid();
        mkdir($this->tempDir);

        $this->tempFile = $this->tempDir . '/test.json';
        file_put_contents($this->tempFile, '{"example": "data"}');

        // Also create a subdirectory and a file inside it
        mkdir($this->tempDir . '/sub');
        file_put_contents($this->tempDir . '/sub/subtest.json', '{"subexample": "data"}');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        array_map('unlink', glob($this->tempDir . '/sub/*'));
        rmdir($this->tempDir . '/sub');

        array_map('unlink', glob($this->tempDir . '/*'));
        rmdir($this->tempDir);
    }

    public function test_it_reads_single_file_correctly(): void
    {
        $reader = new Reader();
        $paths = $reader->read($this->tempFile)->get();

        $this->assertCount(1, $paths);
        $this->assertSame($this->tempFile, $paths[0]);
    }

    public function test_it_reads_all_files_recursively_in_directory(): void
    {
        $reader = new Reader();
        $paths = $reader->read($this->tempDir)->get();

        $expectedPaths = [
            $this->tempFile,
            $this->tempDir . '/sub/subtest.json',
        ];

        sort($paths);
        sort($expectedPaths);

        $this->assertSame($expectedPaths, $paths);
    }

    public function test_it_throws_exception_when_path_is_not_readable(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches('/not readable/');

        $reader = new Reader();
        $reader->read('/non/existing/path/shouldfail.json');
    }

    public function test_it_throws_exception_when_no_path_is_provided(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches('/No path provided/');

        // Set lexicons.source to null using existing ConfigManager
        container()->get(ConfigManager::class)->set('lexicons.source', null);

        $reader = new Reader();
        $reader->read(); // No path given
    }

    public function test_constructor_path_is_used_if_no_argument_given_to_read(): void
    {
        $reader = new Reader($this->tempDir);
        $paths = $reader->read()->get();

        $expectedPaths = [
            $this->tempFile,
            $this->tempDir . '/sub/subtest.json',
        ];

        sort($paths);
        sort($expectedPaths);

        $this->assertSame($expectedPaths, $paths);
    }
}
