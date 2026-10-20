# Releasing

`main` is the only long-lived branch. Changes reach it through pull requests, merge commits only, and
every pull request and push to `main` must pass the `build` check (`.github/workflows/build.yml`: validate
`composer.json`, lint every PHP file and run the offline tests on PHP 8.1, 8.2, 8.3, 8.4 and 8.5). The
`integration` jobs in the same workflow call the API with the `CONVERT_API_SECRET` secret; they are shown
on the pull request but not required, so an API outage cannot block a merge. Merging publishes nothing.

There is no package to build or upload: [Packagist](https://packagist.org/packages/convertapi/convertapi-php)
publishes every `v*` tag it sees, through the repository's GitHub webhook. So a tag is the release, and
`v*` tags are created only by publishing a **GitHub Release** (repository admins only, by ruleset), which
`tomasr78` does:

1. In a pull request, set `ConvertApi::VERSION` in `lib/ConvertApi/ConvertApi.php` to the new version
   (it goes into the `User-Agent` of every request) and add the version to `CHANGELOG.md`. Merge it.
2. Wait for the `build` check of that merge on `main` to pass, then publish the release:

```bash
gh release create v3.0.1 --repo ConvertAPI/convertapi-library-php --target main --title v3.0.1 --generate-notes
```

That runs `.github/workflows/release.yml` in the `packagist` environment, which:

1. refuses a Release published or re-run by anyone else;
2. checks the tag is SemVer (`v3.0.1`, `v3.1.0-beta.1`), that the GitHub pre-release tick matches the
   version, that the tagged commit is on `main`, and that `ConvertApi::VERSION` at the tag is the version;
3. runs every test, the API ones included, on PHP 8.5 against `CONVERT_API_BASE` (a repository variable;
   the production API when unset) with `CONVERT_API_SECRET`;
4. waits up to 15 minutes for <https://repo.packagist.org/p2/convertapi/convertapi-php.json> to serve the
   version from the tagged commit.

Packagist serves the version as soon as it sees the tag, so these checks cannot stop a release; a red run
means the version that is already public needs a fix. **Pre-releases** (`--prerelease`, e.g.
`v3.1.0-beta.1`) work the same way and are also tagged on `main`; Composer installs them only for
`minimum-stability` below `stable` or an explicit constraint.

Packagist versions are effectively permanent: users' `composer.lock` files pin the tag's commit. Never
delete, move or re-create a release tag. Fix a bad version by releasing the next one and say so in both
Releases' notes.

If Packagist stops picking up tags (`release.yml` times out), check the webhook under the repository's
Settings → Webhooks (`https://packagist.org/api/github?username=…`) and press **Update** on
<https://packagist.org/packages/convertapi/convertapi-php> (Packagist maintainers only).
