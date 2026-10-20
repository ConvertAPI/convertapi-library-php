# Changelog

All notable changes to this project will be documented in this file.

The format is based on Keep a Changelog and this project adheres to Semantic Versioning. Releases before
3.0.1 are described in the [GitHub Releases](https://github.com/ConvertAPI/convertapi-library-php/releases).

## [3.0.1] — 2026-10-20

### Fixed
- No more `curl_close() is deprecated` notices on PHP 8.5. The client no longer calls `curl_close()`,
  which has done nothing since PHP 8.0; cURL handles are freed when they go out of scope.
- `ResultFile::getContents()` is documented as returning `false|string`, as `file_get_contents()` does
  ([#65](https://github.com/ConvertAPI/convertapi-library-php/pull/65)).

### Changed
- `ConvertApi::VERSION`, sent in the `User-Agent`, is `3.0.1`.
- Added `examples/vanilla_php.php`, a conversion with plain PHP cURL and no client library.
- The default branch is `main` (was `master`). Composer users requiring `dev-master` should require
  `dev-main` or a tagged version.
- Releases are now cut from a GitHub Release `v{version}` on `main`, checked by `release.yml`. Every pull
  request is built on PHP 8.1, 8.2, 8.3, 8.4 and 8.5.

[3.0.1]: https://github.com/ConvertAPI/convertapi-library-php/compare/v3.0.0...v3.0.1
