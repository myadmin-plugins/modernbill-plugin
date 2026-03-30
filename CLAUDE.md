# MyAdmin ModernBill Plugin

PHP plugin integrating ModernBill legacy billing into the MyAdmin admin panel. Provides client records, invoices, and packages via ACL-gated admin views.

## Commands

```bash
composer install
vendor/bin/phpunit
vendor/bin/phpunit tests/ -v
```

## Architecture

**Namespace:** `Detain\MyAdminModernBill\` → `src/` · Tests: `Detain\MyAdminModernBill\Tests\` → `tests/`

**Entry:** `src/Plugin.php` · **DB helpers:** `src/modernbill.functions.inc.php` · **MB utils:** `src/mb.php`

**Page handlers** (`src/`): `modernbill_client.php` · `modernbill_invoice.php` · `modernbill_invoices.php` · `modernbill_packages.php`

**Tests** (`tests/`): `PluginTest.php` · `ModernBillFunctionsTest.php` · `MbFunctionsTest.php` · `PageFilesTest.php` · `FileExistenceTest.php`

**CI/CD:** `.github/` contains `workflows/tests.yml` for automated testing · **IDE:** `.idea/` contains PhpStorm configuration including `inspectionProfiles/`, `deployment.xml`, and `encodings.xml`

## Plugin Lifecycle

`Plugin::getHooks()` returns event → callback map registered via Symfony `EventDispatcher`:
- `function.requirements` → `Plugin::getRequirements()` — lazy-registers page/function paths

All paths registered under `/../vendor/detain/myadmin-modernbill-plugin/src/`.

## DB Pattern

```php
$db = get_module_db('mb');  // module identifier is always 'mb'
$db->query("SELECT * FROM client_info WHERE client_id=$id", __LINE__, __FILE__);
if ($db->num_rows() > 0) {
    $db->next_record(MYSQL_ASSOC);
    return $db->Record;
}
return false;
```

- Always escape user strings: `$db->real_escape($value)`
- Cast IDs to int: `$id = (int)$id` before interpolation
- Never use PDO

## ACL Pattern

```php
function_requirements('has_acl');
if ($GLOBALS['tf']->ima == 'admin' && has_acl('client_billing')) {
    // admin: query all records
} else {
    $data = $GLOBALS['tf']->accounts->data;
    // user: filter by client_email=" . $db->real_escape($data['account_lid']) . "
}
```

## Page Handler Pattern

```php
function modernbill_example() {
    page_title('ModernBill Example');
    add_js('tablesorter');
    $db = get_module_db('mb');
    $data = $GLOBALS['tf']->accounts->data;
    $table = new TFTable();
    function_requirements('has_acl');
    // ACL-gated query ...
    $smarty = new TFSmarty();
    $smarty->assign('table_header', $headers);
    $smarty->assign('table_rows', $rows);
    add_output($smarty->fetch('tablesorter/tablesorter.tpl'));
}
```

## Conventions

- Tabs for indentation (enforced in `.scrutinizer.yml`)
- Function names: `snake_case` · Class properties: `camelCase`
- Commit messages: lowercase, descriptive (`fix acl check`, `add invoice filter`)
- Run `caliber refresh` before committing; stage modified doc files

<!-- caliber:managed:pre-commit -->
## Before Committing

**IMPORTANT:** Before every git commit, you MUST ensure Caliber syncs agent configs with the latest code changes.

First, check if the pre-commit hook is already installed:
```bash
grep -q "caliber" .git/hooks/pre-commit 2>/dev/null && echo "hook-active" || echo "no-hook"
```

- If **hook-active**: the hook handles sync automatically — just commit normally. Tell the user: "Caliber will sync your agent configs automatically via the pre-commit hook."
- If **no-hook**: run Caliber manually before committing:
  1. Tell the user: "Caliber: Syncing agent configs with your latest changes..."
  2. Run: `caliber refresh && git add CLAUDE.md .claude/ .cursor/ .cursorrules .github/copilot-instructions.md .github/instructions/ AGENTS.md CALIBER_LEARNINGS.md .agents/ .opencode/ 2>/dev/null`
  3. After it completes, briefly tell the user what Caliber updated. Then proceed with the commit.

If `caliber` is not found, tell the user: "This project uses Caliber for agent config sync. Run /setup-caliber to get set up."
<!-- /caliber:managed:pre-commit -->

<!-- caliber:managed:learnings -->
## Session Learnings

Read `CALIBER_LEARNINGS.md` for patterns and anti-patterns learned from previous sessions.
These are auto-extracted from real tool usage — treat them as project-specific rules.
<!-- /caliber:managed:learnings -->

<!-- caliber:managed:sync -->
## Context Sync

This project uses [Caliber](https://github.com/caliber-ai-org/ai-setup) to keep AI agent configs in sync across Claude Code, Cursor, Copilot, and Codex.
Configs update automatically before each commit via `caliber refresh`.
If the pre-commit hook is not set up, run `/setup-caliber` to configure everything automatically.
<!-- /caliber:managed:sync -->
