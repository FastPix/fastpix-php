# GET Endpoints Validation (Hybrid: OpenAPI via Node, SDK via PHP)

## Quick Start

1. Install Node deps:

```bash
# From the SDK repo root
npm install --prefix tests
```

2. Ensure PHP SDK dependencies are installed:

```bash
# From the SDK repo root
composer install
```

3. Set env vars:

```bash
export FASTPIX_USERNAME="your-username"
export FASTPIX_PASSWORD="your-password"
# optional:
# export FASTPIX_BASE_URL="https://api.fastpix.com/v1/"
```

4. Run:

```bash
cd tests
npm run validate:get-endpoints
```

Artifacts and reports are written into `tests/`.

## Overview

The validation script implements a **hybrid testing approach**:

1. **Calls the API directly** via HTTP to get raw JSON responses
2. **Validates API responses** against OpenAPI schema using `openapi-response-validator`
3. **Calls PHP SDK methods** via PHP subprocess execution
4. **Compares API vs SDK responses** to identify:
   - Fields missing in SDK (present in API but dropped by SDK parsing)
   - Fields missing in API (present in SDK but not in API response)
   - Empty arrays omitted in SDK vs API
5. **Generates artifacts** (API vs SDK JSON files) and validation reports

## How It Works

### TypeScript Script (`validate-get-endpoints.ts`)

1. **Loads OpenAPI spec** from `openapi.yaml` at the repo root
2. **Extracts all GET endpoints** from the spec
3. **For each endpoint**:
   - Makes direct HTTP call to API using fixtures
   - Validates raw response against OpenAPI schema
   - Invokes PHP SDK method via subprocess
   - Compares JSON paths between API and SDK responses
   - Writes artifacts (`.api.json` and `.sdk.json` files)
4. **Generates reports**:
   - `GET_ENDPOINTS_OPENAPI_RESPONSE_VALIDATION_REPORT.md` - Detailed validation results
   - `GET_ENDPOINTS_OPENAPI_RESPONSE_FIX_SUGGESTIONS.md` - Suggested fixes for failures
   - Updates `README.md` with consolidated summary

### PHP SDK Invocation

The script calls PHP SDK methods via `php -r` subprocess. It only looks for `php` in `/usr/local/bin`, `/opt/homebrew/bin`, `/usr/bin` and `/bin`; if your PHP lives elsewhere (Herd, asdf, a custom prefix), symlink it into one of those directories. It passes:
- Operation ID
- Request parameters (from fixtures or defaults)
- Base URL
- Credentials (username/password)

PHP code serializes SDK response objects to JSON for comparison.

## Fixtures

The `get-endpoints-fixtures.json` file contains real IDs for endpoints that require path parameters. Update this file with working IDs from your FastPix account for accurate testing.

If fixtures are missing, the script will use placeholder UUIDs, which may result in 404 errors.

<!-- BEGIN GET_ENDPOINTS_CONSOLIDATED -->
_Populated by a validator run. Results are local; do not commit them._
<!-- END GET_ENDPOINTS_CONSOLIDATED -->
