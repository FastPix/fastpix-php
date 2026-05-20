# Changelog

## [1.0.3]

### ⚠️ Important — FastPix is migrating from `.io` to `.com`

All FastPix hosts and documentation links are moving to the `.com` TLD:

| Old (`.io`) | New (`.com`) |
|---|---|
| `api.fastpix.io` | `api.fastpix.com` |
| `stream.fastpix.io` | `stream.fastpix.com` |
| `images.fastpix.io` | `images.fastpix.com` |
| `dashboard.fastpix.io` | `dashboard.fastpix.com` |
| `www.fastpix.io` | `www.fastpix.com` |
| `docs.fastpix.io/...` | `fastpix.com/docs/...` |

The `.io` hosts continue to serve traffic during the transition, but **they are slated for deprecation soon** — please update any hard-coded references in your application as part of your next deploy. **We strongly recommend upgrading to this SDK release (or later) across every language you use** — every official FastPix SDK is being rolled out with the same migration.

What this means for users of `fastpix/sdk`:

- **If you rely on SDK defaults**, no code change is required. The default server URL is `https://api.fastpix.com/v1/`, so bumping to `1.0.3` and running `composer update fastpix/sdk` is enough.
- **If you have an explicit server-URL override** (e.g. `Fastpixsdk::builder()->setServerUrl('https://api.fastpix.io/v1/')`), change it to `https://api.fastpix.com/v1/`.
- **If you reference FastPix asset URLs directly** in your app (HLS playback URLs, image CDN, dashboard deep links), update those to the `.com` equivalents before `.io` is decommissioned.

All README and per-service doc links in this package have been updated to point at the new `https://fastpix.com/docs/...` URLs.

### Fixed (SDK ↔ API parity)

- `manageVideos.listMedia` (`/on-demand`): tracks now include `frameRate`, which was being silently dropped by the previous SDK build.
- `manageVideos.listMedia` / `getMedia` / `retrieveMediaInputInfo`: audio tracks now retain `languageCode` and `languageName` — union deserialization previously resolved every track to the video track type and discarded the language fields.
- `playback.createMediaPlaybackId` (`/on-demand/{mediaId}/playback-ids`): response `data.resolution` is now correctly modelled as nullable — it is `null` when no resolution constraint was set at create time.
- `signingKeys.deleteSigningKey` (`/iam/signing-keys/{signingKeyId}`): response shape now includes the optional `data.message` confirmation string the API has been returning.
- `metrics.listOverallValues` / `metrics.getTimeseriesData`: fixed a PSR-4 autoload mismatch (`MetricsOverallmetadataDetails` / `MetricsTimeseriesmetadataDetails` filename casing) that caused these calls to throw during deserialization.
- OpenAPI `tracks[].type` for `VideoTrack` / `VideoTrackForGetAll` / `AudioTrack` / `SubtitleTrack` is now a proper `enum` (`video` / `audio` / `subtitle`) so the union can be discriminated correctly.
- OpenAPI `maxDuration.minimum` on live-stream schemas relaxed from `60` to `0` to reflect the API's "unbounded" sentinel value.

### Docs

- All README and per-service documentation pages updated from `docs.fastpix.io/...` and `docs.fastpix.com/...` to the new `https://fastpix.com/docs/...` URL structure.

## [1.0.2]
### Fixed
- Fixed data event field remapping in hooks.

## [1.0.1]
 ### Fixed
- Fixed missing parameters in multiple API methods.

### Improved
- Improved overall developer experience through more accurate typings.

## [1.0.0] 

### Added
- Media API: Upload media assets, list, fetch, update, and delete media
- Generate and manage playback IDs
- Live API: Create, list, update, and delete live streams
- Support simulcasting to multiple platforms
- AI-powered features: video summarization, chapter generation, content moderation
- Analytics: view tracking, performance metrics, error monitoring
- Security: signing key management and JWT token support
- Playlist management: create, update, and manage playlists
- DRM configuration support
- Comprehensive error handling and retry logic
- Full PHP 8.2+ type safety with PHPStan analysis
- 163 unit and integration tests
- Complete API coverage (100% of OpenAPI specification)

## [0.1.0] - Beta

### Added
- Initial release of the SDK
- Basic API endpoints
- Core functionality