# DirectUploadVideoMediaRequest

Request body for direct upload


## Fields

| Field                                                                             | Type                                                                              | Required                                                                          | Description                                                                       | Example                                                                           |
| --------------------------------------------------------------------------------- | --------------------------------------------------------------------------------- | --------------------------------------------------------------------------------- | --------------------------------------------------------------------------------- | --------------------------------------------------------------------------------- |
| `corsOrigin`                                                                      | *string*                                                                          | :heavy_check_mark:                                                                | Upload media directly from a device using the URL name or enter '*' to allow all. | *                                                                                 |
| `pushMediaSettings`                                                               | [?Operations\PushMediaSettings](../../Models/Operations/PushMediaSettings.md)     | :heavy_minus_sign:                                                                | Configuration settings for media upload.                                          |                                                                                   |