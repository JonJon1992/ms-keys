# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.3.13] - 2025-01-28

### Fixed

- **Docker**: `setup-dirs.sh` — `chmod 777` em `logs`, `var`, `keystore` para evitar "Permission denied" em `var/temp/im-alive.log` com volume mount (PHP como www-data)

### Changed

- **Scripts**: pequenas alterações em `create-auth-json.sh` e `setup-dirs.sh` (comentários)

## [1.3.12] - 2025-01-28

### Added

- **CI**: Geração de tag de versão no workflow Docker (git tag ou `composer.json`)
- **CI**: Tags semver na imagem (`v1.0.0`, `1.0.0`, `1.0`, `1`) em push de tags
- **Docker**: Entrypoint customizado (`docker-entrypoint.sh`) que roda `setup-dirs.sh` antes do start
- **Docker**: Script `setup-dirs.sh` para criar `logs`, `var/logs`, `var/cache`, `var/temp`, `keystore` e ajustar permissões (uso com volumes)
- **Config**: `cache.php` — opção `scheme` para Redis (env `REDIS_SCHEME`, default `tcp`)
- **Config**: `db.php` — opção `sslmode` para PostgreSQL (env `DB_SSLMODE`, default `require`)

### Changed

- **CI**: Tag `latest` apenas em push para `main` ou `master`; versão da imagem alinhada à tag
- **Docker**: Permissões e ownership de `logs`, `var`, `keystore` no build; `ENTRYPOINT` passa a usar `/docker-entrypoint.sh`
- **Deps**: `jonjon1992/php-slim-modular` `v2.9.13` → `v2.9.14`

[Unreleased]: https://github.com/jonjon1992/ms-keys/compare/v1.3.13...HEAD
[1.3.13]: https://github.com/jonjon1992/ms-keys/compare/v1.3.12...v1.3.13
[1.3.12]: https://github.com/jonjon1992/ms-keys/compare/v1.3.11...v1.3.12
