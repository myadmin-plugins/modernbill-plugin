<?php

declare(strict_types=1);

namespace Detain\MyAdminModernBill\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Tests for functions defined in mb.php.
 *
 * Only pure or safely-testable functions are exercised here.
 * Functions depending on database connections, global state, or
 * deprecated PHP features (e.g. each()) are tested via static analysis only.
 */
class MbFunctionsTest extends TestCase
{
    /**
     * Tests that the mb.php source file exists.
     */
    public function testMbFileExists(): void
    {
        $this->assertFileExists(__DIR__ . '/../src/mb.php');
    }

    /**
     * Tests that the mb.php file declares the smartslashes function.
     */
    public function testSmartslashesFunctionIsDeclared(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('function smartslashes(', $content);
    }

    /**
     * Tests that the mb.php file declares the mb_functions function.
     */
    public function testMbFunctionsFunctionIsDeclared(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('function mb_functions(', $content);
    }

    /**
     * Tests that the mb.php file declares the hex2bin_custom function.
     */
    public function testHex2binCustomFunctionIsDeclared(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('function hex2bin_custom(', $content);
    }

    /**
     * Tests that the mb.php file declares the encrpyt function.
     */
    public function testEncrpytFunctionIsDeclared(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('function encrpyt(', $content);
    }

    /**
     * Tests that the mb.php file declares the readmore_manual function.
     */
    public function testReadmoreManualFunctionIsDeclared(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('function readmore_manual(', $content);
    }

    /**
     * Tests that the mb.php file declares the get_dir_array function.
     */
    public function testGetDirArrayFunctionIsDeclared(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('function get_dir_array(', $content);
    }

    /**
     * Tests that the mb.php file declares the my_array_shift function.
     */
    public function testMyArrayShiftFunctionIsDeclared(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('function my_array_shift(', $content);
    }

    /**
     * Tests that the mb.php file declares the decode_key function.
     */
    public function testDecodeKeyFunctionIsDeclared(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('function decode_key(', $content);
    }

    /**
     * Tests that the mb.php file declares the decode_key1 function.
     */
    public function testDecodeKey1FunctionIsDeclared(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('function decode_key1(', $content);
    }

    /**
     * Tests that the mb.php file declares the secure_access function.
     */
    public function testSecureAccessFunctionIsDeclared(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('function secure_access(', $content);
    }

    /**
     * Tests that the mb.php file declares the sca function.
     */
    public function testScaFunctionIsDeclared(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('function sca(', $content);
    }

    /**
     * Tests that the mb.php file declares the dcc function.
     */
    public function testDccFunctionIsDeclared(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('function dcc(', $content);
    }

    /**
     * Tests that the mb.php file declares the ecc function.
     */
    public function testEccFunctionIsDeclared(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('function ecc(', $content);
    }

    /**
     * Tests that the mb.php file declares the generic_select_menu function.
     */
    public function testGenericSelectMenuFunctionIsDeclared(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('function generic_select_menu(', $content);
    }

    /**
     * Tests that the mb.php file declares the register_session function.
     */
    public function testRegisterSessionFunctionIsDeclared(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('function register_session(', $content);
    }

    /**
     * Tests that the mb.php file declares the return_enckey function.
     */
    public function testReturnEnckeyFunctionIsDeclared(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        $this->assertStringContainsString('function return_enckey(', $content);
    }

    /**
     * Tests that the expected version constant is present in mb.php.
     */
    public function testVersionConstantIsPresent(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        $this->assertStringContainsString("'4.4.1'", $content);
    }

    /**
     * Tests that the mb_functions default case returns the version string.
     */
    public function testMbFunctionsVersionStringInSource(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        // The function sets $retval = '4.4.1' as default and returns it for null input
        $this->assertMatchesRegularExpression(
            '/function\s+mb_functions\s*\(\s*\$t\s*=\s*null\s*\)/',
            $content
        );
    }

    /**
     * Tests that mb_functions handles all expected switch cases.
     */
    public function testMbFunctionsHandlesExpectedCases(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        $expectedCases = [
            'action_functions',
            'auth_functions',
            'cc_functions',
            'db_core_functions',
            'db_functions',
            'display_functions',
            'email_functions',
            'faq_functions',
            'idn_functions',
            'misc_functions',
            'order_functions',
            'pw_functions',
            'select_functions',
            'sql_select_functions',
            'validate_functions',
            'xml_functions',
        ];
        foreach ($expectedCases as $case) {
            $this->assertStringContainsString("'$case'", $content, "Missing case '$case' in mb_functions");
        }
    }

    /**
     * Tests that the encrpyt function implements RC4 as the default fallback cipher.
     */
    public function testEncrpytImplementsRc4AsFallback(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        // RC4 is implemented as the default fallback after the tripleDES and 3DES switch cases
        $this->assertStringContainsString('$pwd_length = mb_strlen($pwd)', $content);
        $this->assertStringContainsString('ord(mb_substr($pwd', $content);
    }

    /**
     * Tests that the encrpyt function supports tripleDES encryption mode.
     */
    public function testEncrpytSupportsTripleDesMode(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        $this->assertStringContainsString("case 'tripleDES'", $content);
    }

    /**
     * Tests that the encrpyt function supports 3DES encryption mode.
     */
    public function testEncrpytSupports3DesMode(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        $this->assertStringContainsString("case '3DES'", $content);
    }

    /**
     * Tests that the secure_access function validates tamper test with sha1 hash.
     */
    public function testSecureAccessValidatesTamperHash(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        $this->assertStringContainsString(
            "sha1('36b18d99b63d16495acdd6a7d4df9531b8920e8b')",
            $content
        );
    }

    /**
     * Tests that the total number of declared functions in mb.php matches expectations.
     */
    public function testExpectedFunctionCount(): void
    {
        $content = file_get_contents(__DIR__ . '/../src/mb.php');
        $this->assertIsString($content);
        preg_match_all('/^\s*function\s+\w+\s*\(/m', $content, $matches);
        $this->assertCount(16, $matches[0], 'mb.php should declare exactly 16 functions');
    }
}
