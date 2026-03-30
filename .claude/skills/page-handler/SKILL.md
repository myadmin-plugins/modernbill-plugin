---
name: page-handler
description: Creates ACL-gated page handler functions matching the pattern in src/modernbill_client.php, src/modernbill_invoices.php, and src/modernbill_packages.php. Includes page_title, add_js('tablesorter'), has_acl('client_billing') guard, TFTable, TFSmarty with tablesorter/tablesorter.tpl, and Plugin.php registration. Use when user says 'add page', 'new view', 'create handler', or adds files to src/. Do NOT use for Plugin.php class methods or DB helper functions in modernbill.functions.inc.php.
---
# Page Handler

## Critical

- **Never use PDO.** Always use `get_module_db('mb')` — the module identifier is always `'mb'`.
- **Always call `function_requirements('has_acl')` before any `has_acl()` call** — it is not auto-loaded.
- **Always escape user strings** with `$db->real_escape($value)` and **cast IDs to int** with `(int)$id` before SQL interpolation.
- **Every new page file must be registered** in `Plugin::getRequirements()` via `$loader->add_page_requirement()`. Skipping this makes the page unreachable.
- Use tabs for indentation (enforced by `.scrutinizer.yml`).

---

## Instructions

### Step 1 — Create the page handler file

Create the page handler file in `src/`. The file contains a single procedural function named `modernbill_{name}()`.

Skeleton (list/table view — the most common case):

```php
<?php
/**
 * modernbill_{name}()
 *
 * @return void
 * @throws \Exception
 * @throws \SmartyException
 */
    function modernbill_{name}()
    {
        add_js('tablesorter');
        page_title('ModernBill {Title}');
        function_requirements('has_acl');
        $data = $GLOBALS['tf']->accounts->data;
        $table = new TFTable();
        function_requirements('get_modernbill_{name}s');
        $rows_data = get_modernbill_{name}s();
        $values = [
            'col_one' => 'Column One',
            'col_two' => 'Column Two',
        ];
        if (count($rows_data) > 0) {
            $smarty = new TFSmarty();
            $smarty->debugging = true;
            $rows = [];
            $smarty->assign('sortcol', 1);
            $smarty->assign('sortdir', 0);
            $smarty->assign('textextraction', "'complex'");
            $title = false;
            foreach ($rows_data as $row) {
                if ($GLOBALS['tf']->ima != 'admin' || !has_acl('client_billing')) {
                    unset($row['client_email']);
                } else {
                    $row['client_email'] = $table->make_link(
                        'choice=none.modernbill_client&amp;id=' . $row['client_id'],
                        $row['client_email']
                    );
                }
                unset($row['client_id']);
                if (!$title) {
                    $title = [];
                    foreach (array_keys($row) as $key) {
                        $title[] = $values[$key];
                    }
                    $smarty->assign('table_header', $title);
                    $title = true;
                }
                $rows[] = $row;
            }
            $smarty->assign('table_rows', $rows);
            add_output($smarty->fetch('tablesorter/tablesorter.tpl'));
        } else {
            add_output('No {Title} Found');
        }
    }
```

**ACL rules (match exactly as used in existing handlers):**
- Admin+ACL sees all records (including `client_email` link to `modernbill_client`).
- Non-admin or no ACL: `unset($row['client_email'])` and filter the DB query by `client_info.client_email = '" . $db->real_escape($data['account_lid']) . "'`.

Verify: the file exists in `src/` and declares exactly one function `modernbill_{name}()`.

---

### Step 2 — Register the page in Plugin.php

Open `src/Plugin.php`. In `getRequirements()`, add a `add_page_requirement` line alongside the existing ones:

```php
$loader->add_page_requirement('modernbill_{name}', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill_{name}.php');
```

If the handler uses a new DB helper function, also register it:

```php
$loader->add_requirement('get_modernbill_{name}s', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill.functions.inc.php');
```

Verify: `Plugin.php` now contains both `add_page_requirement('modernbill_{name}', ...)` lines.

---

### Step 3 — Add the menu entry (if the page should appear in the admin nav)

In `Plugin::getMenu()`, inside the `has_acl('client_billing')` block, add:

```php
$menu->add_link('virtual', 'choice=none.modernbill_{name}', '/lib/webhostinghub-glyphs-icons/icons/business-32/Black/icon-{icon}.png', _('View {Title}'));
```

Verify: the new `add_link` call is inside the `if (has_acl('client_billing'))` guard.

---

### Step 4 — Run tests

```bash
vendor/bin/phpunit tests/ -v
```

`FileExistenceTest` and `PageFilesTest` will catch a missing file or unregistered page. Fix any failures before committing.

---

### Step 5 — Commit

Before committing:

```bash
caliber refresh && git add CLAUDE.md .claude/ .cursor/ AGENTS.md CALIBER_LEARNINGS.md 2>/dev/null
```

Commit message format: lowercase, descriptive — e.g. `add modernbill payments page handler`.

---

## Examples

**User says:** "Add a page to list ModernBill packages"

**Actions taken:**
1. Create `src/modernbill_packages.php` with function `modernbill_packages()`.
2. Call `add_js('tablesorter')`, `page_title('ModernBill Client Package Information')`.
3. `function_requirements('has_acl')` then `function_requirements('get_modernbill_packages')`.
4. Fetch `$packages = get_modernbill_packages()`; build `$values` map.
5. In loop: non-admins get `unset($package['client_email'])`; admins get a `make_link(...)` to `modernbill_client`.
6. Assign `table_header` (from `$values` map on first iteration) and `table_rows` to `TFSmarty`; fetch `tablesorter/tablesorter.tpl`.
7. In `Plugin::getRequirements()` add `add_page_requirement('modernbill_packages', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill_packages.php')`.
8. In `Plugin::getMenu()` add `add_link('virtual', 'choice=none.modernbill_packages', ...)` inside the ACL block.

**Result:** matches `src/modernbill_packages.php` exactly as committed.

---

## Common Issues

**`Call to undefined function has_acl()`**
You forgot `function_requirements('has_acl')` before the `has_acl()` call. Add it as the first line after `$table = new TFTable()`.

**Page returns 404 / "choice not found"**
The page was not registered. Verify `Plugin::getRequirements()` has `$loader->add_page_requirement('modernbill_{name}', '...')` with the correct path.

**`FileExistenceTest` or `PageFilesTest` fails**
File path in `add_page_requirement` doesn't match the actual file. The path argument must start with `'/../vendor/detain/myadmin-modernbill-plugin/src/'` (leading `/../` is required).

**`$smarty->fetch()` throws `SmartyException: unable to load template`**
The template name must be exactly `'tablesorter/tablesorter.tpl'` — no leading slash, no `.php` extension.

**Table shows no column headers**
The `$title` sentinel pattern requires `$title = false` before the loop and the `if (!$title) { ... $title = true; }` block inside the loop on the first iteration. Do not assign `table_header` outside the loop.

**SQL returns rows for wrong user**
For non-admin paths, filter with `client_info.client_email = '" . $db->real_escape($data['account_lid']) . "'` — use `$db->real_escape()`, never interpolate `$_GET`/`$_POST` directly.
