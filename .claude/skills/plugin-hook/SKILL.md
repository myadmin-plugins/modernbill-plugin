---
name: plugin-hook
description: Adds or modifies Symfony EventDispatcher hooks in src/Plugin.php. Covers getHooks() map entries, add_page_requirement and add_requirement calls with vendor paths, and static method signatures accepting GenericEvent. Use when user says 'add hook', 'register page', 'add requirement', or modifies Plugin.php. Do NOT use for non-plugin class files or page handler files in src/*.php. NOTE: for a plugin's contract/behavioral tests (tests/ContractTest.php, the shared harness, composer myadmin:scaffold-tests) use the plugin-contract-tests skill instead — this skill's reflection-only guidance predates that harness.
---
<!-- myadmin-contract-harness-notice -->
> ### ⚠️ Read this before the rest of the file
>
> This package is on the **shared plugin contract harness**. Parts of the guidance below
> predate it and are now wrong in one specific way:
>
> **Any instruction here that a plugin's `getHooks()` / `getSettings()` / `getActivate()` /
> `getDeactivate()` / `getQueue()` must not be *called* — that only its existence, visibility
> or parameter count may be checked through `ReflectionClass` — no longer applies.** That rule
> existed because those methods reference bare constants (`PRORATE_BILLING` and friends) that
> only a live MyAdmin request defines, so calling them from a test used to fatal. The harness
> defines them first. It then executes the handlers for real, in a process of its own.
>
> A reflection-only assertion passes whether or not the thing works: `getActivate()` can exist,
> be public, be static, take one argument, and still fatal the moment it runs. Three real
> production bugs in this fleet were sitting behind assertions of exactly that shape.
>
> **Use the `plugin-contract-tests` skill** for anything touching `tests/ContractTest.php`,
> the contract inspectors, or `composer myadmin:scaffold-tests`.
>
> **Everything else in this file is still accurate and still applies** — this package's own
> classes, its API wrappers, its fixtures, its bootstrap, and the reasons certain classes must
> not be constructed. Nothing below has been removed.

# Plugin Hook

## Critical

- Every handler method MUST be `public static` and accept exactly one parameter: `GenericEvent $event` — PHPUnit reflection tests assert this.
- Every new handler MUST be registered in `getHooks()` using `[__CLASS__, 'methodName']` — never a closure or string.
- All paths in `getRequirements()` MUST start with `'/../vendor/detain/myadmin-modernbill-plugin/src/'` — tests assert this prefix on every registered path.
- Unused/inactive hooks go in `getHooks()` as commented-out lines, NOT deleted.
- Tabs for indentation — `.scrutinizer.yml` enforces this. Never spaces.

## Instructions

### Step 1 — Add the event key to `getHooks()`

Open `src/Plugin.php`. Add a new entry to the array returned by `getHooks()`:

```php
public static function getHooks()
{
    return [
        'function.requirements' => [__CLASS__, 'getRequirements'],
        'your.event.name'       => [__CLASS__, 'yourHandlerMethod'],  // add here
    ];
}
```

Verify: the key is the exact Symfony event name (dot-separated string), the value is `[__CLASS__, '<method>']`, and the method you reference exists or will be created in Step 2.

### Step 2 — Add the static handler method

Add the method in `src/Plugin.php` inside the `Plugin` class, after existing handlers. Follow this exact signature:

```php
/**
 * @param \Symfony\Component\EventDispatcher\GenericEvent $event
 */
public static function yourHandlerMethod(GenericEvent $event)
{
    $subject = $event->getSubject();
    // implementation here
}
```

- For `function.requirements`-style handlers, the subject is `\MyAdmin\Plugins\Loader $loader`.
- For `ui.menu`-style handlers, the subject is the menu object (see `getMenu()`).
- For `system.settings`-style handlers, the subject is `\MyAdmin\Settings $settings`.

Verify: method is `public static`, parameter is typed `GenericEvent $event`, doc block references the full class path.

### Step 3 — Register pages and functions inside `getRequirements()`

When adding a new page handler file (`src/modernbill_example.php`) or a new function file:

```php
public static function getRequirements(GenericEvent $event)
{
    /** @var \MyAdmin\Plugins\Loader $this->loader */
    $loader = $event->getSubject();

    // Page handler (maps choice=none.modernbill_example URL → file)
    $loader->add_page_requirement('modernbill_example', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill_example.php');

    // Function requirement (maps function name → file that defines it)
    $loader->add_requirement('get_modernbill_example', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill.functions.inc.php');
}
```

- `add_page_requirement($name, $path)` — use for page-handler functions invoked via `choice=none.<name>`.
- `add_requirement($funcName, $path)` — use for helper functions; multiple functions from the same file each get their own `add_requirement` call.
- Path always starts with `'/../vendor/detain/myadmin-modernbill-plugin/src/'`.

Verify: run `vendor/bin/phpunit tests/PluginTest.php` — all assertions about path prefixes and count must pass.

### Step 4 — Run tests

```bash
vendor/bin/phpunit tests/PluginTest.php -v
```

The test `testAllRegisteredPathsContainVendorPrefix` will catch any wrong path prefix. `testAllHookCallbacksAreCallableStaticMethods` will catch missing or non-static methods.

## Examples

**User says:** "Add a hook for a new modernbill_reports page and a `get_modernbill_reports` function."

**Actions taken:**

1. In `getHooks()` — already has `function.requirements` mapped; no new entry needed since reports will use the existing hook.
2. In `getRequirements()` — add two lines:

```php
$loader->add_page_requirement('modernbill_reports', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill_reports.php');
$loader->add_requirement('get_modernbill_reports', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill.functions.inc.php');
```

3. Create `src/modernbill_reports.php` with the page handler function `modernbill_reports()`.
4. Add `get_modernbill_reports()` to `src/modernbill.functions.inc.php`.
5. Run `vendor/bin/phpunit tests/PluginTest.php -v`.

**User says:** "Register a new `system.settings` hook."

1. Uncomment (or add) in `getHooks()`:
```php
'system.settings' => [__CLASS__, 'getSettings'],
```
2. `getSettings()` already exists with correct signature — implement body:
```php
public static function getSettings(GenericEvent $event)
{
    /** @var \MyAdmin\Settings $settings **/
    $settings = $event->getSubject();
    // add setting registration calls here
}
```

## Common Issues

**Error: `testAllHookCallbacksAreCallableStaticMethods` fails with "Method does not exist"**
You added an entry to `getHooks()` but didn't create the corresponding method, or mistyped the method name. Check the second element of the callback array matches the exact method name in the class.

**Error: `testAllRegisteredPathsContainVendorPrefix` fails**
A path passed to `add_page_requirement` or `add_requirement` is missing the `'/../vendor/detain/myadmin-modernbill-plugin/src/'` prefix. All paths must use this exact prefix string — no absolute filesystem paths.

**Error: `testGetRequirementsRegistersFourPageRequirements` or `testGetRequirementsRegistersFiveFunctionRequirements` fails with wrong count**
You added or removed a `add_page_requirement`/`add_requirement` call without updating the corresponding test assertions in `tests/PluginTest.php`. Update the `assertCount` values to match the new totals.

**Error: `testGetRequirementsAcceptsGenericEvent` or similar reflection test fails**
Handler method is not typed. Signature must be exactly `public static function yourMethod(GenericEvent $event)` — the `GenericEvent` type hint is required.

**Error: `Call to undefined function get_module_db()`** during integration
Page handler files loaded via `add_page_requirement` run inside the MyAdmin host environment. The functions `get_module_db()`, `function_requirements()`, etc. are only available at runtime — not in unit tests. Keep unit tests focused on `Plugin.php` structure; use the anonymous loader pattern from `PluginTest.php` to test `getRequirements()` in isolation.
