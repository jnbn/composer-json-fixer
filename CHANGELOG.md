# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

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
