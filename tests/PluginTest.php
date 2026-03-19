<?php

declare(strict_types=1);

namespace Detain\MyAdminModernBill\Tests;

use Detain\MyAdminModernBill\Plugin;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Tests for the Plugin class.
 */
class PluginTest extends TestCase
{
    /**
     * @var ReflectionClass<Plugin>
     */
    private ReflectionClass $reflection;

    protected function setUp(): void
    {
        $this->reflection = new ReflectionClass(Plugin::class);
    }

    /**
     * Tests that the Plugin class can be instantiated.
     */
    public function testPluginCanBeInstantiated(): void
    {
        $plugin = new Plugin();
        $this->assertInstanceOf(Plugin::class, $plugin);
    }

    /**
     * Tests that the $name static property is set correctly.
     */
    public function testNamePropertyIsCorrect(): void
    {
        $this->assertSame('ModernBill Plugin', Plugin::$name);
    }

    /**
     * Tests that the $description static property is set correctly.
     */
    public function testDescriptionPropertyIsCorrect(): void
    {
        $this->assertSame(
            'Allows handling of ModernBill based Payments through their Payment Processor/Payment System.',
            Plugin::$description
        );
    }

    /**
     * Tests that the $help static property is an empty string.
     */
    public function testHelpPropertyIsEmptyString(): void
    {
        $this->assertSame('', Plugin::$help);
    }

    /**
     * Tests that the $type static property is 'plugin'.
     */
    public function testTypePropertyIsPlugin(): void
    {
        $this->assertSame('plugin', Plugin::$type);
    }

    /**
     * Tests that the Plugin class has exactly four static properties.
     */
    public function testPluginHasExpectedStaticProperties(): void
    {
        $staticProperties = $this->reflection->getStaticProperties();
        $this->assertArrayHasKey('name', $staticProperties);
        $this->assertArrayHasKey('description', $staticProperties);
        $this->assertArrayHasKey('help', $staticProperties);
        $this->assertArrayHasKey('type', $staticProperties);
        $this->assertCount(4, $staticProperties);
    }

    /**
     * Tests that all static properties are of type string.
     */
    public function testAllStaticPropertiesAreStrings(): void
    {
        $this->assertIsString(Plugin::$name);
        $this->assertIsString(Plugin::$description);
        $this->assertIsString(Plugin::$help);
        $this->assertIsString(Plugin::$type);
    }

    /**
     * Tests that getHooks returns an array.
     */
    public function testGetHooksReturnsArray(): void
    {
        $hooks = Plugin::getHooks();
        $this->assertIsArray($hooks);
    }

    /**
     * Tests that getHooks contains the function.requirements hook.
     */
    public function testGetHooksContainsFunctionRequirements(): void
    {
        $hooks = Plugin::getHooks();
        $this->assertArrayHasKey('function.requirements', $hooks);
    }

    /**
     * Tests that the function.requirements hook points to getRequirements.
     */
    public function testFunctionRequirementsHookPointsToGetRequirements(): void
    {
        $hooks = Plugin::getHooks();
        $expected = [Plugin::class, 'getRequirements'];
        $this->assertSame($expected, $hooks['function.requirements']);
    }

    /**
     * Tests that getHooks returns exactly one active hook.
     */
    public function testGetHooksReturnsOneActiveHook(): void
    {
        $hooks = Plugin::getHooks();
        $this->assertCount(1, $hooks);
    }

    /**
     * Tests that getMenu is a public static method.
     */
    public function testGetMenuIsPublicStatic(): void
    {
        $method = $this->reflection->getMethod('getMenu');
        $this->assertTrue($method->isPublic());
        $this->assertTrue($method->isStatic());
    }

    /**
     * Tests that getRequirements is a public static method.
     */
    public function testGetRequirementsIsPublicStatic(): void
    {
        $method = $this->reflection->getMethod('getRequirements');
        $this->assertTrue($method->isPublic());
        $this->assertTrue($method->isStatic());
    }

    /**
     * Tests that getSettings is a public static method.
     */
    public function testGetSettingsIsPublicStatic(): void
    {
        $method = $this->reflection->getMethod('getSettings');
        $this->assertTrue($method->isPublic());
        $this->assertTrue($method->isStatic());
    }

    /**
     * Tests that getHooks is a public static method.
     */
    public function testGetHooksIsPublicStatic(): void
    {
        $method = $this->reflection->getMethod('getHooks');
        $this->assertTrue($method->isPublic());
        $this->assertTrue($method->isStatic());
    }

    /**
     * Tests that getMenu accepts a GenericEvent parameter.
     */
    public function testGetMenuAcceptsGenericEvent(): void
    {
        $method = $this->reflection->getMethod('getMenu');
        $params = $method->getParameters();
        $this->assertCount(1, $params);
        $this->assertSame('event', $params[0]->getName());
        $paramType = $params[0]->getType();
        $this->assertNotNull($paramType);
        $this->assertSame(GenericEvent::class, $paramType->getName());
    }

    /**
     * Tests that getRequirements accepts a GenericEvent parameter.
     */
    public function testGetRequirementsAcceptsGenericEvent(): void
    {
        $method = $this->reflection->getMethod('getRequirements');
        $params = $method->getParameters();
        $this->assertCount(1, $params);
        $this->assertSame('event', $params[0]->getName());
        $paramType = $params[0]->getType();
        $this->assertNotNull($paramType);
        $this->assertSame(GenericEvent::class, $paramType->getName());
    }

    /**
     * Tests that getSettings accepts a GenericEvent parameter.
     */
    public function testGetSettingsAcceptsGenericEvent(): void
    {
        $method = $this->reflection->getMethod('getSettings');
        $params = $method->getParameters();
        $this->assertCount(1, $params);
        $this->assertSame('event', $params[0]->getName());
        $paramType = $params[0]->getType();
        $this->assertNotNull($paramType);
        $this->assertSame(GenericEvent::class, $paramType->getName());
    }

    /**
     * Tests that the constructor takes no parameters.
     */
    public function testConstructorTakesNoParameters(): void
    {
        $constructor = $this->reflection->getConstructor();
        $this->assertNotNull($constructor);
        $this->assertCount(0, $constructor->getParameters());
    }

    /**
     * Tests that the class is in the correct namespace.
     */
    public function testClassIsInCorrectNamespace(): void
    {
        $this->assertSame('Detain\\MyAdminModernBill', $this->reflection->getNamespaceName());
    }

    /**
     * Tests that the class is not abstract.
     */
    public function testClassIsNotAbstract(): void
    {
        $this->assertFalse($this->reflection->isAbstract());
    }

    /**
     * Tests that the class is not an interface.
     */
    public function testClassIsNotInterface(): void
    {
        $this->assertFalse($this->reflection->isInterface());
    }

    /**
     * Tests that all hook callbacks reference callable static methods.
     */
    public function testAllHookCallbacksAreCallableStaticMethods(): void
    {
        $hooks = Plugin::getHooks();
        foreach ($hooks as $eventName => $callback) {
            $this->assertIsArray($callback, "Hook '$eventName' callback should be an array");
            $this->assertCount(2, $callback, "Hook '$eventName' callback should have exactly 2 elements");
            [$class, $method] = $callback;
            $this->assertTrue(
                method_exists($class, $method),
                "Method $class::$method referenced in hook '$eventName' does not exist"
            );
            $refMethod = new ReflectionMethod($class, $method);
            $this->assertTrue(
                $refMethod->isStatic(),
                "Method $class::$method referenced in hook '$eventName' is not static"
            );
        }
    }

    /**
     * Tests that the Plugin class has exactly five public methods (constructor + 4 static).
     */
    public function testPluginHasExpectedPublicMethods(): void
    {
        $methods = $this->reflection->getMethods(ReflectionMethod::IS_PUBLIC);
        $methodNames = array_map(fn(ReflectionMethod $m) => $m->getName(), $methods);
        $this->assertContains('__construct', $methodNames);
        $this->assertContains('getHooks', $methodNames);
        $this->assertContains('getMenu', $methodNames);
        $this->assertContains('getRequirements', $methodNames);
        $this->assertContains('getSettings', $methodNames);
    }

    /**
     * Tests that getRequirements calls add_page_requirement and add_requirement on the loader.
     */
    public function testGetRequirementsRegistersExpectedPages(): void
    {
        $pageRequirements = [];
        $requirements = [];

        $loader = new class($pageRequirements, $requirements) {
            /** @var array<int, array{0: string, 1: string}> */
            private array $pageReqs;
            /** @var array<int, array{0: string, 1: string}> */
            private array $reqs;

            /**
             * @param array<int, array{0: string, 1: string}> $pageReqs
             * @param array<int, array{0: string, 1: string}> $reqs
             */
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
        $this->assertContains('modernbill_invoice', $pageNames);
        $this->assertContains('modernbill_invoices', $pageNames);
        $this->assertContains('modernbill_packages', $pageNames);

        $reqNames = array_column($requirements, 0);
        $this->assertContains('get_modernbill_client_by_id', $reqNames);
        $this->assertContains('get_modernbill_client_by_email', $reqNames);
        $this->assertContains('get_modernbill_clients', $reqNames);
        $this->assertContains('get_modernbill_invoices', $reqNames);
        $this->assertContains('get_modernbill_packages', $reqNames);
    }

    /**
     * Tests that getRequirements registers exactly 4 page requirements.
     */
    public function testGetRequirementsRegistersFourPageRequirements(): void
    {
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

        $this->assertCount(4, $pageRequirements);
    }

    /**
     * Tests that getRequirements registers exactly 5 function requirements.
     */
    public function testGetRequirementsRegistersFiveFunctionRequirements(): void
    {
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

        $this->assertCount(5, $requirements);
    }

    /**
     * Tests that all registered paths contain the expected vendor path prefix.
     */
    public function testAllRegisteredPathsContainVendorPrefix(): void
    {
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

        $allPaths = array_merge(
            array_column($pageRequirements, 1),
            array_column($requirements, 1)
        );

        foreach ($allPaths as $path) {
            $this->assertStringContainsString(
                'vendor/detain/myadmin-modernbill-plugin/src/',
                $path,
                "Path '$path' should contain the vendor prefix"
            );
        }
    }

    /**
     * Tests that getSettings retrieves the subject from the event without error.
     */
    public function testGetSettingsRetrievesSubject(): void
    {
        $settingsObj = new \stdClass();
        $event = new GenericEvent($settingsObj);
        Plugin::getSettings($event);
        // getSettings only assigns $settings from subject; if no exception, it passed
        $this->assertSame($settingsObj, $event->getSubject());
    }
}
