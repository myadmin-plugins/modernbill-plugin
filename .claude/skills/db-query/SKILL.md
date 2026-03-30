---
name: db-query
description: Writes ModernBill DB queries using get_module_db('mb'). Handles single-row lookup (num_rows check + next_record), multi-row fetch (while loop), real_escape for strings, and int-cast for IDs. Use when user says 'query modernbill', 'look up client', 'fetch invoices from DB', or adds functions to src/modernbill.functions.inc.php. Do NOT use for non-ModernBill modules or PDO.
---
# db-query

## Critical

- **Never use PDO.** Always use `get_module_db('mb')` — the module identifier is always `'mb'` for ModernBill.
- **Always pass `__LINE__, __FILE__`** as the second and third arguments to every `$db->query()` call.
- **Never interpolate raw `$_GET`/`$_POST` strings.** Escape with `$db->real_escape($value)` before use.
- **Always cast IDs to int** before interpolating: `$id = (int)$id`.
- **Never use PDO** — not even once, not even for convenience.
- All new DB functions belong in `src/modernbill.functions.inc.php`.

## Instructions

### Step 1 — Choose the query shape

Decide which of three patterns fits:

| Shape | When to use |
|---|---|
| Single-row by int ID | Lookup by primary key (`client_id`, `invoice_id`, etc.) |
| Single-row by string | Lookup by email or other string column |
| Multi-row | Fetch all records, optionally ACL-filtered |

Verify the table and column names against the existing queries in `src/modernbill.functions.inc.php` before writing SQL.

### Step 2 — Write a single-row lookup by int ID

```php
/**
 * get_modernbill_THING_by_id()
 * gets a modernbill THING by ID
 *
 * @param integer $id the ID of the modernbill THING to get
 * @return false|array returns false if not found or the array of THING info
 */
function get_modernbill_THING_by_id($id)
{
	$id = (int)$id;
	$db = get_module_db('mb');
	$db->query("select * from THING_table where THING_id=$id", __LINE__, __FILE__);
	if ($db->num_rows() > 0) {
		$db->next_record(MYSQL_ASSOC);
		return $db->Record;
	}
	return false;
}
```

Verify: `(int)$id` cast is present. `__LINE__, __FILE__` present. Returns `false` on miss.

### Step 3 — Write a single-row lookup by string

```php
/**
 * get_modernbill_THING_by_email()
 *
 * @param string $email
 * @return false|array
 */
function get_modernbill_THING_by_email($email)
{
	$db = get_module_db('mb');
	$db->query("select * from THING_table where THING_email='" . $db->real_escape($email) . "'", __LINE__, __FILE__);
	if ($db->num_rows() > 0) {
		$db->next_record(MYSQL_ASSOC);
		return $db->Record;
	}
	return false;
}
```

Verify: `$db->real_escape()` wraps the string. Single quotes surround the value in SQL.

### Step 4 — Write a multi-row fetch (no ACL)

Use `clone $GLOBALS['mb_dbh']` when iterating all records to avoid handle conflicts:

```php
function get_modernbill_THINGS($fields = false)
{
	if ($fields === false) {
		$fields = [];
	}
	if (count($fields) == 0) {
		$fields[] = '*';
	}
	$db = clone $GLOBALS['mb_dbh'];
	$db->query('select ' . implode(', ', $fields) . ' from THING_table', __LINE__, __FILE__);
	$results = [];
	if ($db->num_rows() > 0) {
		while ($db->next_record(MYSQL_ASSOC)) {
			$results[] = $db->Record;
		}
	}
	return $results;
}
```

Verify: uses `clone $GLOBALS['mb_dbh']` (not `get_module_db`). `while` loop with `$db->next_record(MYSQL_ASSOC)`. Returns `[]` on miss.

### Step 5 — Write a multi-row fetch with ACL filtering

Use `get_module_db('mb')` (not clone) when the query varies by role:

```php
function get_modernbill_THINGS_acl()
{
	$db = get_module_db('mb');
	$data = $GLOBALS['tf']->accounts->data;
	function_requirements('has_acl');
	if ($GLOBALS['tf']->ima == 'admin' && has_acl('client_billing')) {
		$query = 'SELECT ... FROM THING_table';
	} else {
		$query = "SELECT ... FROM THING_table WHERE client_email='" . $db->real_escape($data['account_lid']) . "'";
	}
	$db->query($query, __LINE__, __FILE__);
	$results = [];
	while ($db->next_record(MYSQL_ASSOC)) {
		$results[] = $db->Record;
	}
	return $results;
}
```

Verify: `function_requirements('has_acl')` called before the ACL check. Non-admin branch filters by `$db->real_escape($data['account_lid'])`. No `num_rows()` guard needed on the `while` loop for multi-row fetches.

### Step 6 — Register the function (if new)

In `src/Plugin.php`, inside `getRequirements()`, add the function file mapping so `function_requirements()` can lazy-load it:

```php
$loader->add_requirement('get_modernbill_THING_by_id', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill.functions.inc.php');
```

Verify: path starts with `'/../vendor/detain/myadmin-modernbill-plugin/src/'`.

### Step 7 — Run tests

```bash
vendor/bin/phpunit tests/ -v
```

Verify all tests pass before committing.

## Examples

**User says:** "Add a function to look up a ModernBill invoice by invoice ID"

**Actions taken:**
1. Check `src/modernbill.functions.inc.php` — confirm table is `client_invoice`, PK is `invoice_id`.
2. Add to `src/modernbill.functions.inc.php`:

```php
/**
 * get_modernbill_invoice_by_id()
 * gets a modernbill invoice by ID
 *
 * @param integer $id the ID of the invoice to get
 * @return false|array returns false if not found or the array of invoice info
 */
function get_modernbill_invoice_by_id($id)
{
	$id = (int)$id;
	$db = get_module_db('mb');
	$db->query("select * from client_invoice where invoice_id=$id", __LINE__, __FILE__);
	if ($db->num_rows() > 0) {
		$db->next_record(MYSQL_ASSOC);
		return $db->Record;
	}
	return false;
}
```

3. Add to `Plugin::getRequirements()` in `src/Plugin.php`:
```php
$loader->add_requirement('get_modernbill_invoice_by_id', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill.functions.inc.php');
```
4. Run `vendor/bin/phpunit tests/ -v`.

**Result:** New function follows identical structure to `get_modernbill_client_by_id()` on line 18 of `src/modernbill.functions.inc.php`.

## Common Issues

**"Call to undefined function get_module_db()"**  
This function is provided by the MyAdmin host environment. Run tests via `vendor/bin/phpunit` (the bootstrap stubs it). Do not call `get_module_db` outside the test harness without the host loaded.

**"Call to undefined function has_acl()"**  
You forgot `function_requirements('has_acl');` before the ACL check. Add it immediately before the `if ($GLOBALS['tf']->ima == 'admin' ...)` line.

**Query returns empty but rows exist**  
Check that string values are wrapped in single quotes in SQL and escaped: `'" . $db->real_escape($val) . "'`. A missing quote means the WHERE clause matches nothing silently.

**Multi-row fetch returns only the first row**  
You used `if ($db->num_rows() > 0) { $db->next_record(...); return $db->Record; }` instead of a `while` loop. Replace with `while ($db->next_record(MYSQL_ASSOC)) { $results[] = $db->Record; }`.

**Tests fail with "undefined index: mb_dbh"**  
You used `clone $GLOBALS['mb_dbh']` in a function that should use `get_module_db('mb')` (or vice versa). Use `clone $GLOBALS['mb_dbh']` only for the field-selectable `get_modernbill_clients()` pattern. Use `get_module_db('mb')` for all ACL-gated and single-row functions.

**Indentation style errors flagged by Scrutinizer**  
Use tabs, not spaces. The `.scrutinizer.yml` enforces tabs throughout `src/`.
