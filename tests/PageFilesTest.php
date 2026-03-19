<?php

declare(strict_types=1);

namespace Detain\MyAdminModernBill\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Tests for the page-level source files (modernbill_client.php,
 * modernbill_invoice.php, modernbill_invoices.php, modernbill_packages.php).
 *
 * These files define page-rendering functions that depend heavily on
 * global state, database connections, and template engines. We verify
 * their structure and expected declarations via static analysis.
 */
class PageFilesTest extends TestCase
{
    /**
     * Tests that modernbill_client.php file exists.
     */
    public function testModernbillClientFileExists(): void
    {
        $this->assertFileExists(__DIR__ . '/../src/modernbill_client.php');
    }

    /**
     * Tests that modernbill_invoice.php file exists.
     */
    public function testModernbillInvoiceFileExists(): void
    {
        $this->assertFileExists(__DIR__ . '/../src/modernbill_invoice.php');
    }

    /**
     * Tests that modernbill_invoices.php file exists.
     */
    public function testModernbillInvoicesFileExists(): void
    {
        $this->assertFileExists(__DIR__ . '/../src/modernbill_invoices.php');
    }

    /**
     * Tests that modernbill_packages.php file exists.
     */
    public function testModernbillPackagesFileExists(): void
    {
        $this->assertFileExists(__DIR__ . '/../src/modernbill_packages.php');
    }

    /**
     * Tests that modernbill_client.php declares the modernbill_client function.
     */
    public function testModernbillClientFunctionIsDeclared(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/modernbill_client.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('function modernbill_client(', $content);
    }

    /**
     * Tests that modernbill_invoice.php declares the modernbill_invoice function.
     */
    public function testModernbillInvoiceFunctionIsDeclared(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/modernbill_invoice.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('function modernbill_invoice(', $content);
    }

    /**
     * Tests that modernbill_invoices.php declares the modernbill_invoices function.
     */
    public function testModernbillInvoicesFunctionIsDeclared(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/modernbill_invoices.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('function modernbill_invoices(', $content);
    }

    /**
     * Tests that modernbill_packages.php declares the modernbill_packages function.
     */
    public function testModernbillPackagesFunctionIsDeclared(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/modernbill_packages.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('function modernbill_packages(', $content);
    }

    /**
     * Tests that modernbill_client.php uses get_module_db for database access.
     */
    public function testModernbillClientUsesModuleDb(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/modernbill_client.php');
        $this->assertIsString($content);
        $this->assertStringContainsString("get_module_db('mb')", $content);
    }

    /**
     * Tests that modernbill_invoice.php uses get_module_db for database access.
     */
    public function testModernbillInvoiceUsesModuleDb(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/modernbill_invoice.php');
        $this->assertIsString($content);
        $this->assertStringContainsString("get_module_db('mb')", $content);
    }

    /**
     * Tests that modernbill_client.php checks admin ACL.
     */
    public function testModernbillClientChecksAdminAcl(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/modernbill_client.php');
        $this->assertIsString($content);
        $this->assertStringContainsString("has_acl('client_billing')", $content);
    }

    /**
     * Tests that modernbill_invoice.php checks admin ACL.
     */
    public function testModernbillInvoiceChecksAdminAcl(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/modernbill_invoice.php');
        $this->assertIsString($content);
        $this->assertStringContainsString("has_acl('client_billing')", $content);
    }

    /**
     * Tests that modernbill_client.php sets page title.
     */
    public function testModernbillClientSetsPageTitle(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/modernbill_client.php');
        $this->assertIsString($content);
        $this->assertStringContainsString("page_title('ModernBill Client Information')", $content);
    }

    /**
     * Tests that modernbill_invoices.php sets page title.
     */
    public function testModernbillInvoicesSetsPageTitle(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/modernbill_invoices.php');
        $this->assertIsString($content);
        $this->assertStringContainsString("page_title('ModernBill Client Invoice Information')", $content);
    }

    /**
     * Tests that modernbill_packages.php sets page title.
     */
    public function testModernbillPackagesSetsPageTitle(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/modernbill_packages.php');
        $this->assertIsString($content);
        $this->assertStringContainsString("page_title('ModernBill Client Package Information')", $content);
    }

    /**
     * Tests that modernbill_invoice.php uses number_format for currency display.
     */
    public function testModernbillInvoiceUsesNumberFormat(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/modernbill_invoice.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('number_format(', $content);
    }

    /**
     * Tests that modernbill_invoice.php references PayPal payment URL.
     */
    public function testModernbillInvoiceReferencesPaypal(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/modernbill_invoice.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('paypal.com', $content);
    }

    /**
     * Tests that modernbill_invoice.php uses bcadd for balance calculations.
     */
    public function testModernbillInvoiceUsesBcadd(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/modernbill_invoice.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('bcadd(', $content);
    }

    /**
     * Tests that modernbill_invoice.php uses bcsub for balance calculations.
     */
    public function testModernbillInvoiceUsesBcsub(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/modernbill_invoice.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('bcsub(', $content);
    }

    /**
     * Tests that modernbill_invoices.php uses tablesorter JS.
     */
    public function testModernbillInvoicesUsesTablesorter(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/modernbill_invoices.php');
        $this->assertIsString($content);
        $this->assertStringContainsString("add_js('tablesorter')", $content);
    }

    /**
     * Tests that modernbill_packages.php uses tablesorter JS.
     */
    public function testModernbillPackagesUsesTablesorter(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/modernbill_packages.php');
        $this->assertIsString($content);
        $this->assertStringContainsString("add_js('tablesorter')", $content);
    }

    /**
     * Tests that modernbill_client.php declares exactly one function.
     */
    public function testModernbillClientDeclaresOneFunction(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/modernbill_client.php');
        $this->assertIsString($content);
        preg_match_all('/^\s*function\s+\w+\s*\(/m', $content, $matches);
        $this->assertCount(1, $matches[0]);
    }

    /**
     * Tests that modernbill_invoice.php declares exactly one function.
     */
    public function testModernbillInvoiceDeclaresOneFunction(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/modernbill_invoice.php');
        $this->assertIsString($content);
        preg_match_all('/^\s*function\s+\w+\s*\(/m', $content, $matches);
        $this->assertCount(1, $matches[0]);
    }

    /**
     * Tests that modernbill_invoices.php declares exactly one function.
     */
    public function testModernbillInvoicesDeclaresOneFunction(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/modernbill_invoices.php');
        $this->assertIsString($content);
        preg_match_all('/^\s*function\s+\w+\s*\(/m', $content, $matches);
        $this->assertCount(1, $matches[0]);
    }

    /**
     * Tests that modernbill_packages.php declares exactly one function.
     */
    public function testModernbillPackagesDeclaresOneFunction(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/modernbill_packages.php');
        $this->assertIsString($content);
        preg_match_all('/^\s*function\s+\w+\s*\(/m', $content, $matches);
        $this->assertCount(1, $matches[0]);
    }

    /**
     * Tests that all source PHP files start with a PHP opening tag.
     */
    public function testAllSourceFilesStartWithPhpTag(): void
    {
        $files = [
            'modernbill_client.php',
            'modernbill_invoice.php',
            'modernbill_invoices.php',
            'modernbill_packages.php',
        ];
        foreach ($files as $file) {
            $content = file_get_contents(__DIR__ . '/../src/' . $file);
            $this->assertIsString($content);
            $this->assertStringStartsWith('<?php', $content, "$file should start with <?php");
        }
    }

    /**
     * Tests that modernbill_packages.php defines expected column values mapping.
     */
    public function testModernbillPackagesDefinesColumnValues(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/modernbill_packages.php');
        $this->assertIsString($content);
        $this->assertStringContainsString("'client_email' => 'Client'", $content);
        $this->assertStringContainsString("'pack_name' => 'Package'", $content);
        $this->assertStringContainsString("'pack_price' => 'Price'", $content);
    }
}
