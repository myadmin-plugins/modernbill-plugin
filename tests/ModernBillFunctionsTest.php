<?php

declare(strict_types=1);

namespace Detain\MyAdminModernBill\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Tests for functions in modernbill.functions.inc.php.
 *
 * These functions all depend on database connections and global state,
 * so we test them via static analysis of the source file.
 */
class ModernBillFunctionsTest extends TestCase
{
    /**
     * @var string
     */
    private string $sourceContent;

    protected function setUp(): void
    {
        $path = __DIR__ . '/../src/modernbill.functions.inc.php';
        $content = file_get_contents($path);
        $this->assertIsString($content);
        $this->sourceContent = $content;
    }

    /**
     * Tests that the modernbill.functions.inc.php file exists.
     */
    public function testFileExists(): void
    {
        $this->assertFileExists(__DIR__ . '/../src/modernbill.functions.inc.php');
    }

    /**
     * Tests that get_modernbill_client_by_id function is declared.
     */
    public function testGetModernbillClientByIdIsDeclared(): void
    {
        $this->assertStringContainsString('function get_modernbill_client_by_id(', $this->sourceContent);
    }

    /**
     * Tests that get_modernbill_client_by_email function is declared.
     */
    public function testGetModernbillClientByEmailIsDeclared(): void
    {
        $this->assertStringContainsString('function get_modernbill_client_by_email(', $this->sourceContent);
    }

    /**
     * Tests that get_modernbill_clients function is declared.
     */
    public function testGetModernbillClientsIsDeclared(): void
    {
        $this->assertStringContainsString('function get_modernbill_clients(', $this->sourceContent);
    }

    /**
     * Tests that get_modernbill_invoices function is declared.
     */
    public function testGetModernbillInvoicesIsDeclared(): void
    {
        $this->assertStringContainsString('function get_modernbill_invoices(', $this->sourceContent);
    }

    /**
     * Tests that get_modernbill_packages function is declared.
     */
    public function testGetModernbillPackagesIsDeclared(): void
    {
        $this->assertStringContainsString('function get_modernbill_packages(', $this->sourceContent);
    }

    /**
     * Tests that the file declares exactly five functions.
     */
    public function testFileDeclaresExactlyFiveFunctions(): void
    {
        preg_match_all('/^\s*function\s+\w+\s*\(/m', $this->sourceContent, $matches);
        $this->assertCount(5, $matches[0]);
    }

    /**
     * Tests that get_modernbill_client_by_id casts input to integer for safety.
     */
    public function testClientByIdCastsToInt(): void
    {
        $this->assertStringContainsString('(int)$id', $this->sourceContent);
    }

    /**
     * Tests that get_modernbill_client_by_email uses real_escape for SQL safety.
     */
    public function testClientByEmailUsesRealEscape(): void
    {
        $this->assertStringContainsString('real_escape($email)', $this->sourceContent);
    }

    /**
     * Tests that get_modernbill_clients accepts an optional fields parameter.
     */
    public function testGetClientsAcceptsFieldsParameter(): void
    {
        $this->assertMatchesRegularExpression(
            '/function\s+get_modernbill_clients\s*\(\s*\$fields\s*=\s*false\s*\)/',
            $this->sourceContent
        );
    }

    /**
     * Tests that get_modernbill_client_by_id returns false when no rows found.
     */
    public function testClientByIdReturnsFalseOnNoRows(): void
    {
        $this->assertStringContainsString('return false;', $this->sourceContent);
    }

    /**
     * Tests that get_modernbill_invoices queries client_invoice table.
     */
    public function testInvoicesQueriesClientInvoiceTable(): void
    {
        $this->assertStringContainsString('client_invoice', $this->sourceContent);
    }

    /**
     * Tests that get_modernbill_packages queries client_package table.
     */
    public function testPackagesQueriesClientPackageTable(): void
    {
        $this->assertStringContainsString('client_package', $this->sourceContent);
    }

    /**
     * Tests that get_modernbill_invoices joins with client_info table.
     */
    public function testInvoicesJoinsClientInfo(): void
    {
        $this->assertStringContainsString('LEFT OUTER JOIN client_info', $this->sourceContent);
    }

    /**
     * Tests that get_modernbill_packages joins with package_type table.
     */
    public function testPackagesJoinsPackageType(): void
    {
        $this->assertStringContainsString('LEFT JOIN package_type', $this->sourceContent);
    }

    /**
     * Tests that get_modernbill_clients defaults to selecting all fields.
     */
    public function testGetClientsDefaultsToAllFields(): void
    {
        $this->assertStringContainsString("\$fields[] = '*'", $this->sourceContent);
    }

    /**
     * Tests that get_modernbill_invoices checks admin ACL.
     */
    public function testInvoicesChecksAdminAcl(): void
    {
        $this->assertStringContainsString("has_acl('client_billing')", $this->sourceContent);
    }

    /**
     * Tests that get_modernbill_packages checks admin ACL.
     */
    public function testPackagesChecksAdminAcl(): void
    {
        // Count occurrences: should be in both invoices and packages functions
        $count = substr_count($this->sourceContent, "has_acl('client_billing')");
        $this->assertGreaterThanOrEqual(2, $count);
    }

    /**
     * Tests that all query functions use MYSQL_ASSOC fetch mode.
     */
    public function testAllQueriesUseMysqlAssoc(): void
    {
        $count = substr_count($this->sourceContent, 'MYSQL_ASSOC');
        $this->assertGreaterThanOrEqual(5, $count, 'All DB functions should use MYSQL_ASSOC');
    }

    /**
     * Tests that the module database identifier is "mb".
     */
    public function testModuleDatabaseIdentifierIsMb(): void
    {
        $this->assertStringContainsString("get_module_db('mb')", $this->sourceContent);
    }
}
