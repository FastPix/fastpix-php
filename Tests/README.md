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

1. **Loads OpenAPI spec** from `fastpix-openapi.yaml` (searches multiple locations)
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

The script calls PHP SDK methods via `php -r` subprocess, passing:
- Operation ID
- Request parameters (from fixtures or defaults)
- Base URL
- Credentials (username/password)

PHP code serializes SDK response objects to JSON for comparison.

## Fixtures

The `get-endpoints-fixtures.json` file contains real IDs for endpoints that require path parameters. Update this file with working IDs from your FastPix account for accurate testing.

If fixtures are missing, the script will use placeholder UUIDs, which may result in 404 errors.

<!-- BEGIN GET_ENDPOINTS_CONSOLIDATED -->
Last generated: 2026-06-08T09:38:30.144Z

- **Total GET endpoints**: 30
- **PASS**: 25
- **FAIL**: 5
- **SKIP**: 0

| Endpoint | OperationId | OpenAPI valid | SDK parse | Missing in SDK (present in API) | Missing in API (present in SDK) | Empty arrays omitted by SDK | Status |
|---|---|---:|---:|---|---|---|---|
| `/on-demand` | `list-media` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/on-demand/{livestreamId}/live-clips` | `list-live-clips` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/on-demand/{mediaId}` | `get-media` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/on-demand/{mediaId}/summary` | `get-media-summary` | ✅ | ❌ | None | None | None | ❌ FAIL |
| `/on-demand/{mediaId}/input-info` | `retrieveMediaInputInfo` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/on-demand/{mediaId}/playback-ids` | `list-playback-ids` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/on-demand/uploads` | `list-uploads` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/on-demand/{mediaId}/media-clips` | `get-media-clips` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/on-demand/playlists` | `get-all-playlists` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/on-demand/playlists/{playlistId}` | `get-playlist-by-id` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/on-demand/{mediaId}/playback-ids/{playbackId}` | `get-playback-id` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/on-demand/drm-configurations` | `getDrmConfiguration` | ✅ | ❌ | None | None | None | ❌ FAIL |
| `/on-demand/drm-configurations/{drmConfigurationId}` | `getDrmConfigurationById` | ✅ | ❌ | None | None | None | ❌ FAIL |
| `/live/streams` | `get-all-streams` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/live/streams/{streamId}/viewer-count` | `get-live-stream-viewer-count-by-id` | ✅ | ❌ | None | None | None | ❌ FAIL |
| `/live/streams/{streamId}` | `get-live-stream-by-id` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/live/streams/{streamId}/playback-ids/{playbackId}` | `get-live-stream-playback-id` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/live/streams/{streamId}/simulcast/{simulcastId}` | `get-specific-simulcast-of-stream` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/iam/signing-keys` | `list_signing_keys` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/iam/signing-keys/{signingKeyId}` | `get-signing_key_by_id` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/data/viewlist` | `list_video_views` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/data/viewlist/{viewId}` | `get_video_view_details` | ✅ | ✅ | `data.custom.Device`, `data.custom.Device[]`, `data.custom.Device[].dimensionName`, `data.custom.Device[].displayName` | None | None | ❌ FAIL |
| `/data/viewlist/top-content` | `list_by_top_content` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/data/dimensions` | `list_dimensions` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/data/dimensions/{dimensionsId}` | `list_filter_values_for_dimension` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/data/metrics/{metricId}/breakdown` | `list_breakdown_values` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/data/metrics/{metricId}/overall` | `list_overall_values` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/data/metrics/{metricId}/timeseries` | `get_timeseries_data` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/data/metrics/comparison` | `list_comparison_values` | ✅ | ✅ | None | None | None | ✅ PASS |
| `/data/errors` | `list_errors` | ✅ | ✅ | None | None | None | ✅ PASS |

#### Missing fields (full lists)

- **list-media** (`/on-demand`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **list-live-clips** (`/on-demand/{livestreamId}/live-clips`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **get-media** (`/on-demand/{mediaId}`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **get-media-summary** (`/on-demand/{mediaId}/summary`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **retrieveMediaInputInfo** (`/on-demand/{mediaId}/input-info`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **list-playback-ids** (`/on-demand/{mediaId}/playback-ids`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **list-uploads** (`/on-demand/uploads`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **get-media-clips** (`/on-demand/{mediaId}/media-clips`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **get-all-playlists** (`/on-demand/playlists`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **get-playlist-by-id** (`/on-demand/playlists/{playlistId}`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **get-playback-id** (`/on-demand/{mediaId}/playback-ids/{playbackId}`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **getDrmConfiguration** (`/on-demand/drm-configurations`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **getDrmConfigurationById** (`/on-demand/drm-configurations/{drmConfigurationId}`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **get-all-streams** (`/live/streams`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **get-live-stream-viewer-count-by-id** (`/live/streams/{streamId}/viewer-count`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **get-live-stream-by-id** (`/live/streams/{streamId}`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **get-live-stream-playback-id** (`/live/streams/{streamId}/playback-ids/{playbackId}`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **get-specific-simulcast-of-stream** (`/live/streams/{streamId}/simulcast/{simulcastId}`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **list_signing_keys** (`/iam/signing-keys`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **get-signing_key_by_id** (`/iam/signing-keys/{signingKeyId}`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **list_video_views** (`/data/viewlist`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **get_video_view_details** (`/data/viewlist/{viewId}`)
  - **Missing in SDK (present in API)**: `data.custom.Device`, `data.custom.Device[]`, `data.custom.Device[].dimensionName`, `data.custom.Device[].displayName`
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **list_by_top_content** (`/data/viewlist/top-content`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **list_dimensions** (`/data/dimensions`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **list_filter_values_for_dimension** (`/data/dimensions/{dimensionsId}`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **list_breakdown_values** (`/data/metrics/{metricId}/breakdown`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **list_overall_values** (`/data/metrics/{metricId}/overall`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **get_timeseries_data** (`/data/metrics/{metricId}/timeseries`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **list_comparison_values** (`/data/metrics/comparison`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None
- **list_errors** (`/data/errors`)
  - **Missing in SDK (present in API)**: None
  - **Missing in API (present in SDK)**: None
  - **Empty arrays omitted by SDK**: None
  - **Empty arrays omitted by API**: None

Full details: `tests/GET_ENDPOINTS_OPENAPI_RESPONSE_VALIDATION_REPORT.md`
<!-- END GET_ENDPOINTS_CONSOLIDATED -->
