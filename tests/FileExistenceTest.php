<?php

declare(strict_types=1);

namespace Detain\MyAdminModernBill\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Tests that all expected source files and configuration files exist
 * in the package directory structure.
 */
class FileExistenceTest extends TestCase
{
    /**
     * Tests that the src directory exists.
     */
    public function testSrcDirectoryExists(): void
    {
        $this->assertDirectoryExists(__DIR__ . '/../src');
    }

    /**
     * Tests that Plugin.php exists in the src directory.
     */
    public function testPluginPhpExists(): void
    {
        $this->assertFileExists(__DIR__ . '/../src/Plugin.php');
    }

    /**
     * Tests that mb.php exists in the src directory.
     */
    public function testMbPhpExists(): void
    {
        $this->assertFileExists(__DIR__ . '/../src/mb.php');
    }

    /**
     * Tests that modernbill.functions.inc.php exists in the src directory.
     */
    public function testModernbillFunctionsFileExists(): void
    {
        $this->assertFileExists(__DIR__ . '/../src/modernbill.functions.inc.php');
    }

    /**
     * Tests that modernbill_client.php exists in the src directory.
     */
    public function testModernbillClientFileExists(): void
    {
        $this->assertFileExists(__DIR__ . '/../src/modernbill_client.php');
    }

    /**
     * Tests that modernbill_invoice.php exists in the src directory.
     */
    public function testModernbillInvoiceFileExists(): void
    {
        $this->assertFileExists(__DIR__ . '/../src/modernbill_invoice.php');
    }

    /**
     * Tests that modernbill_invoices.php exists in the src directory.
     */
    public function testModernbillInvoicesFileExists(): void
    {
        $this->assertFileExists(__DIR__ . '/../src/modernbill_invoices.php');
    }

    /**
     * Tests that modernbill_packages.php exists in the src directory.
     */
    public function testModernbillPackagesFileExists(): void
    {
        $this->assertFileExists(__DIR__ . '/../src/modernbill_packages.php');
    }

    /**
     * Tests that composer.json exists in the package root.
     */
    public function testComposerJsonExists(): void
    {
        $this->assertFileExists(__DIR__ . '/../composer.json');
    }

    /**
     * Tests that there are exactly seven source files.
     */
    public function testSrcDirectoryContainsExpectedFileCount(): void
    {
        $files = glob(__DIR__ . '/../src/*.php');
        $this->assertIsArray($files);
        $this->assertCount(7, $files);
    }

    /**
     * Tests that all source files are valid PHP (no syntax errors detectable via token_get_all).
     */
    public function testAllSourceFilesAreValidPhp(): void
    {
        $files = glob(__DIR__ . '/../src/*.php');
        $this->assertIsArray($files);
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $this->assertIsString($content, "Could not read $file");
            // token_get_all will throw a ParseError for invalid PHP in PHP 8
            $tokens = token_get_all($content);
            $this->assertIsArray($tokens, "Failed to tokenize $file");
            $this->assertNotEmpty($tokens, "$file produced no tokens");
        }
    }
}
