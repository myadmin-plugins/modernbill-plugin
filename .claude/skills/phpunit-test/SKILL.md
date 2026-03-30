---
name: phpunit-test
description: Writes PHPUnit tests following patterns in tests/PluginTest.php, tests/ModernBillFunctionsTest.php, and tests/PageFilesTest.php. Covers file-existence assertions, function-declaration string checks, and ReflectionClass method inspection. Use when user says 'add test', 'write tests for', or adds files to tests/. Do NOT use for integration tests requiring a live DB or for testing runtime behavior that depends on global state ($GLOBALS['tf'], get_module_db).
---
# PHPUnit Test

## Critical

- **Never** require or include source files that depend on `get_module_db`, `$GLOBALS['tf']`, `TFTable`, `TFSmarty`, or any MyAdmin globals. These are unavailable in the test runner — tests will fatal error.
- **Never** call procedural page handler functions directly. Use static analysis (read file contents, assert strings) instead.
- All test files **must** begin with `declare(strict_types=1);` — PHPUnit config enforces this.
- Namespace **must** be `Detain\MyAdminModernBill\Tests;` for every test class.
- Use tabs for indentation (enforced by `.scrutinizer.yml`).
- Run `vendor/bin/phpunit tests/ -v` to verify — a test that fatals is worse than no test.

## Instructions

### 1. Create the test file

Place new test files in `tests/`. Name: `ClassNameTest.php` (PascalCase, `Test` suffix). For example: `PluginTest.php`, `ModernBillFunctionsTest.php`.

Boilerplate header — copy exactly:

```php
<?php

declare(strict_types=1);

namespace Detain\MyAdminModernBill\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Tests for {subject}.
 */
class {Subject}Test extends TestCase
{
}
```

Add `use ReflectionClass;`, `use ReflectionMethod;`, `use Symfony\Component\EventDispatcher\GenericEvent;` only when actually used.

Verify: `vendor/bin/phpunit tests/ --no-coverage` exits 0 before adding assertions.

### 2. File-existence tests (for any new `src/` file)

Pattern from `FileExistenceTest.php` and `PageFilesTest.php`:

```php
public function test{FileName}Exists(): void
{
    $this->assertFileExists(__DIR__ . '/../src/{filename}.php');
}
```

For directory existence: `$this->assertDirectoryExists(__DIR__ . '/../src');`

To assert a PHP file is syntactically valid without executing it:

```php
public function test{FileName}IsValidPhp(): void
{
    $content = file_get_contents(__DIR__ . '/../src/{filename}.php');
    $this->assertIsString($content);
    $tokens = token_get_all($content);
    $this->assertIsArray($tokens);
    $this->assertNotEmpty($tokens);
}
```

Verify: all `assertFileExists` paths resolve to real files before committing.

### 3. Function-declaration tests (for procedural `.php` and `.inc.php` files)

Source files with global functions depend on unavailable runtime state — test them by reading the file as a string. Pattern from `ModernBillFunctionsTest.php` and `MbFunctionsTest.php`:

```php
private string $sourceContent;

protected function setUp(): void
{
    $path = __DIR__ . '/../src/{filename}.php';
    $content = file_get_contents($path);
    $this->assertIsString($content);
    $this->sourceContent = $content;
}

public function test{FunctionName}IsDeclared(): void
{
    $this->assertStringContainsString('function {function_name}(', $this->sourceContent);
}
```

To assert exact function count:

```php
public function testFileDeclaresExactly{N}Functions(): void
{
    preg_match_all('/^\s*function\s+\w+\s*\(/m', $this->sourceContent, $matches);
    $this->assertCount({N}, $matches[0]);
}
```

To assert a function signature (optional parameter, type hint):

```php
$this->assertMatchesRegularExpression(
    '/function\s+{name}\s*\(\s*\$param\s*=\s*false\s*\)/',
    $this->sourceContent
);
```

To assert security patterns are present:

```php
// ID safety
$this->assertStringContainsString('(int)$id', $this->sourceContent);
// String escape
$this->assertStringContainsString('real_escape($email)', $this->sourceContent);
// ACL guard
$this->assertStringContainsString("has_acl('client_billing')", $this->sourceContent);
// DB module
$this->assertStringContainsString("get_module_db('mb')", $this->sourceContent);
```

Verify: run `vendor/bin/phpunit tests/` — all string assertions must pass against current source.

### 4. ReflectionClass tests (for `src/Plugin.php` or other class files)

Pattern from `PluginTest.php` — use when the class can be instantiated without runtime dependencies:

```php
private ReflectionClass $reflection;

protected function setUp(): void
{
    $this->reflection = new ReflectionClass(Plugin::class);
}

// Static property value
public function testNamePropertyIsCorrect(): void
{
    $this->assertSame('Expected Value', Plugin::$name);
}

// Method visibility + static
public function test{Method}IsPublicStatic(): void
{
    $method = $this->reflection->getMethod('{method}');
    $this->assertTrue($method->isPublic());
    $this->assertTrue($method->isStatic());
}

// Method parameter type
public function test{Method}AcceptsGenericEvent(): void
{
    $method = $this->reflection->getMethod('{method}');
    $params = $method->getParameters();
    $this->assertCount(1, $params);
    $this->assertSame('event', $params[0]->getName());
    $paramType = $params[0]->getType();
    $this->assertNotNull($paramType);
    $this->assertSame(GenericEvent::class, $paramType->getName());
}

// All static properties present
public function testPluginHasExpectedStaticProperties(): void
{
    $staticProperties = $this->reflection->getStaticProperties();
    $this->assertArrayHasKey('name', $staticProperties);
    $this->assertCount(4, $staticProperties);
}
```

Verify: `new Plugin()` must not throw — check that the constructor has no required runtime dependencies.

### 5. Testing `getRequirements` with an anonymous loader stub

Pattern from `PluginTest.php` — avoids mocking framework for simple collaborators:

```php
$pageRequirements = [];
$requirements = [];

$loader = new class($pageRequirements, $requirements) {
    private array $pageReqs;
    private array $reqs;

    public function __construct(array &$pageReqs, array &$reqs)
    {
        $this->pageReqs = &$pageReqs;
        $this->reqs = &$reqs;
    }

    public function add_page_requirement(string $name, string $path): void
    {
        $this->pageReqs[] = [$name, $path];
    }

    public function add_requirement(string $name, string $path): void
    {
        $this->reqs[] = [$name, $path];
    }
};

$event = new GenericEvent($loader);
Plugin::getRequirements($event);

$pageNames = array_column($pageRequirements, 0);
$this->assertContains('modernbill_client', $pageNames);
```

To assert all registered paths use the correct vendor prefix:

```php
foreach ($allPaths as $path) {
    $this->assertStringContainsString(
        'vendor/detain/myadmin-modernbill-plugin/src/',
        $path
    );
}
```

Verify: run the specific test method with `--filter=testGetRequirements`.

### 6. Run the full suite

```bash
vendor/bin/phpunit tests/ -v
```

All tests green before committing. Then: `caliber refresh && git add CLAUDE.md .claude/ .cursor/ AGENTS.md CALIBER_LEARNINGS.md 2>/dev/null`.

## Examples

**User says:** "Add tests for a new page handler file."

**Actions taken:**

1. Create the test file in `tests/` following the boilerplate pattern:

```php
<?php

declare(strict_types=1);

namespace Detain\MyAdminModernBill\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Tests for the modernbill_reports.php page handler.
 */
class ModernbillReportsTest extends TestCase
{
	public function testModernbillReportsFileExists(): void
	{
		$this->assertFileExists(__DIR__ . '/../src/modernbill_reports.php');
	}

	public function testModernbillReportsFunctionIsDeclared(): void
	{
		$content = file_get_contents(__DIR__ . '/../src/modernbill_reports.php');
		$this->assertIsString($content);
		$this->assertStringContainsString('function modernbill_reports(', $content);
	}

	public function testModernbillReportsUsesModuleDb(): void
	{
		$content = file_get_contents(__DIR__ . '/../src/modernbill_reports.php');
		$this->assertIsString($content);
		$this->assertStringContainsString("get_module_db('mb')", $content);
	}

	public function testModernbillReportsChecksAdminAcl(): void
	{
		$content = file_get_contents(__DIR__ . '/../src/modernbill_reports.php');
		$this->assertIsString($content);
		$this->assertStringContainsString("has_acl('client_billing')", $content);
	}

	public function testModernbillReportsDeclaresOneFunction(): void
	{
		$content = file_get_contents(__DIR__ . '/../src/modernbill_reports.php');
		$this->assertIsString($content);
		preg_match_all('/^\s*function\s+\w+\s*\(/m', $content, $matches);
		$this->assertCount(1, $matches[0]);
	}
}
```

2. Run `vendor/bin/phpunit tests/ -v` — confirms tests are found and assertions are correct.

**Result:** New test class follows namespace, header, indentation, and static-analysis conventions exactly matching `PageFilesTest.php`.

## Common Issues

**`Fatal error: Call to undefined function get_module_db()`**
Cause: test file requires/includes the source file, which calls `get_module_db()` at load time.
Fix: Remove the `require` or `include`. Use `file_get_contents(__DIR__ . '/../src/file.php')` and assert strings instead.

**`Class 'Detain\MyAdminModernBill\Plugin' not found`**
Cause: autoloader not bootstrapped.
Fix: Run `composer install` first. The `phpunit.xml.dist` bootstraps `vendor/autoload.php` automatically via `<bootstrap>vendor/autoload.php</bootstrap>` — ensure you run `vendor/bin/phpunit` not `php phpunit.phar`.

**`Coding style: tabs required, spaces found` in CI**
Cause: editor inserted spaces instead of tabs.
Fix: Replace leading spaces with tabs. Run `make php-cs-fixer` or check `.scrutinizer.yml` for the tab rule.

**`assertCount(4, $matches[0]) failed, got 5`** (function count mismatch)
Cause: source file was updated with a new function after tests were written, or the regex matches nested closures.
Fix: Check the regex `/^\s*function\s+\w+\s*\(/m` counts only top-level functions. If closures are the issue, scope the assertion to `assertGreaterThanOrEqual` instead of `assertCount`.

**`assertStringContainsString` passes locally but fails in CI**
Cause: line endings differ (CRLF vs LF) or file encoding differs.
Fix: Normalize the source content: `$content = str_replace("\r\n", "\n", $content);` before asserting.

**`ReflectionClass` test fails: method not found**
Cause: asserting a method that was renamed or does not exist on the class.
Fix: Run `grep -r 'function ' src/Plugin.php` to list current methods before writing Reflection assertions.
