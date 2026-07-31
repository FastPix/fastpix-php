# GetMediaDetailResponseMp4Support

A single MP4 rendition generated for the media. Audio-only renditions carry no width or height.


## Fields

| Field    | Type                                                                                                                    | Required           | Example                                                                                                                                     |
| -------- | ----------------------------------------------------------------------------------------------------------------------- | ------------------ | ------------------------------------------------------------------------------------------------------------------------------------------- |
| `type`   | [?Components\GetMediaDetailResponseMp4SupportType](../../Models/Components/GetMediaDetailResponseMp4SupportType.md)     | :heavy_minus_sign: | The MP4 rendition type. `capped_4k` is a downloadable MP4 video capped at 4K resolution, `audioOnly` is a downloadable m4a audio-only file. |
| `status` | [?Components\GetMediaDetailResponseMp4SupportStatus](../../Models/Components/GetMediaDetailResponseMp4SupportStatus.md) | :heavy_minus_sign: | Generation status of this MP4 rendition.                                                                                                    |
| `height` | *?int*                                                                                                                  | :heavy_minus_sign: | Pixel height of the rendition. Omitted for the `audioOnly` type.                                                                            |
| `width`  | *?int*                                                                                                                  | :heavy_minus_sign: | Pixel width of the rendition. Omitted for the `audioOnly` type.                                                                             |
| `ext`    | [?Components\GetMediaDetailResponseMp4SupportExt](../../Models/Components/GetMediaDetailResponseMp4SupportExt.md)       | :heavy_minus_sign: | File extension of the downloadable rendition.                                                                                               |
