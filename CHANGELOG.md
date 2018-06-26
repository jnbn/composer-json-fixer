# CHANGELOG for composer.json fixer

## v2.1.0 - *2018-06-26*
- Add shortcut "x" for "upgrade-dev" option

## v2.0.0 - *2018-06-24*
- Add option "upgrade-dev" for upgrading only dev requirements
- Rename option "with-updates" to "upgrade"
- Drop PHP 5 support

## v1.3.0 - *2017-12-14*
- Fixers refactoring
- Add `ComposerKeysLowercaseFixer`
- Add `ComposerKeysSortingFixer`
- Add `LicenseFixer`
- Rename existing fixers

## v1.2.0 - *2017-07-29*
- Introducing `RemoveDefaultMinimumStabilityFixer`

## v1.1.0 - *2017-07-07*
- Introducing `LowercaseFixer`

## v1.0.0 - *2017-06-30*
- Initial version with fixers:
  - `AutoloadFixer`
  - `RepositoriesFixer`
  - `RequireFixer`
  - `SortingByKeyFixer`
  - `SortingFixer`
  - `UnwantedPropertyFixer`
