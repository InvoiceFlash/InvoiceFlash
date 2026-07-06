---
name: invoiceFlash
description: Reference for InvoiceFlash's architecture — PHP/OpenCart-style MVC (not Electron), the admin/ back-office structure, the Settings/Store route, the VERSION constant, the existing git-based auto-update system (admin/model/tool/upgrade.php), and the install/ installer. Use when working on admin controllers/models/views, the update/upgrade tool, versioning, or the installer.
---

InvoiceFlash is a PHP web app built on an OpenCart-style MVC(C) architecture (Controller/Model/View split, `.tpl` templates, no Node/Electron main-process). **This is NOT Electron.** There is a `package.json` but it only lists frontend asset dependencies (bootstrap, jquery, chartjs) — no Electron, no build tooling.

## 1. Settings/Store route

- [admin/controller/setting/store.php](admin/controller/setting/store.php) — controller, routes `setting/store` (list), insert, update, delete
- [admin/model/setting/store.php](admin/model/setting/store.php) — model
- [admin/view/template/setting/store_list.tpl](admin/view/template/setting/store_list.tpl), [admin/view/template/setting/store_form.tpl](admin/view/template/setting/store_form.tpl) — views
- [admin/language/en-gb/setting/store.php](admin/language/en-gb/setting/store.php) — language strings
- Button convention seen in `store_list.tpl:28`: `<a class="btn btn-default" href="...">icon text</a>`

## 2. App version

- [admin/index.php:3](admin/index.php#L3) and [index.php:3](index.php#L3): `define('VERSION', '0.0.0.8');` — this is the authoritative app version constant, checked at bootstrap.
- `package.json` version (`0.0.0.1`) is stale/unrelated (npm deps only) — do not treat it as the app version.
- The existing update system does **not** compare semantic versions — it compares git commit SHAs (see §4).

## 3. admin/ folder

Core MVC app for the admin/back-office. Structure: `controller/`, `model/`, `view/` (templates, stylesheet, javascript), `language/`, plus `config.php`/`config-dist.php`, `index.php`, `php.ini`. It's a normal part of the repo (not a separately-downloaded/bundled asset) — loaded via `admin/index.php`, the front controller for `/admin`. Same MVC pattern exists for `install/` (installer) and root `system/`/`catalog` presumably for storefront.

## 4. Existing update/auto-update logic — already fully implemented (git-based, not release-based)

- [admin/model/tool/upgrade.php](admin/model/tool/upgrade.php) — `ModelToolUpgrade`: constants `REPO='InvoiceFlash/InvoiceFlash'`, `BRANCH='master'`, `CHECK_INTERVAL=86400`. Methods:
  - `getStatus()`
  - `check()` — calls `https://api.github.com/repos/{REPO}/commits/{BRANCH}` via cURL
  - `getCompareUrl()`
  - `upgrade()` — downloads `https://github.com/{REPO}/archive/refs/heads/{BRANCH}.zip`, extracts with `ZipArchive`, backs up replaced files to `system/backup/backup_<timestamp>.zip`, overlays onto app root, skips `install/` and `php.ini`, never touches `config.php`.
  - State stored in the `setting` DB table, group `upgrade`: `config_update_last_check`, `config_update_current_commit`, `config_update_latest_commit`, `config_update_latest_message`, `config_update_latest_date`, `config_update_last_upgrade`.
- [admin/controller/tool/upgrade.php](admin/controller/tool/upgrade.php) — `ControllerToolUpgrade`: routes `tool/upgrade` (index/page), `tool/upgrade/check` (AJAX JSON), `tool/upgrade/upgrade` (POST-like GET link, permission-gated via `$this->user->hasPermission('modify','tool/upgrade')`).
- [admin/view/template/tool/upgrade.tpl](admin/view/template/tool/upgrade.tpl) — full page with "Check now" / "Update now" (`button_upgrade`) buttons, AJAX check via jQuery, `confirm()` before upgrade.
- [admin/language/en-gb/tool/upgrade.php](admin/language/en-gb/tool/upgrade.php) — strings (`button_upgrade` = "Update now", not yet Spanish "Actualizar").
- [admin/controller/common/header.php:25-34,45-56,983-986](admin/controller/common/header.php#L25-L56) — header injects a global "update available" notification banner (uses `update_available`, `update_url`, `update_can_upgrade`, `text_update_upgrade`/`text_update_confirm` etc.) shown site-wide in admin, plus a menu entry gated by `hasPermission('access','tool/upgrade')`.

## 5. Installer

[install/](install/) — separate mini-MVC app: `install/controller`, `install/model`, `install/view`, `install/index.php`, `install/invoiceflash.sql`. This is unrelated to the runtime update mechanism beyond both writing to similarly-structured DB tables.

## 6. Tech stack

Pure PHP (procedural bootstrap + OOP MVC, OpenCart-derived), MySQL (`system/library` has the DB layer), jQuery/Bootstrap frontend, `.tpl` PHP templates rendered server-side, cURL for outbound HTTP, `ZipArchive` for extraction. No Electron, no Node backend, no SPA framework.
