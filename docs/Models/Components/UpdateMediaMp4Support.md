# UpdateMediaMp4Support

A single MP4 rendition generated for the media. Audio-only renditions carry no width or height.


## Fields

| Field    | Type                                                                                              | Required           | Example                                                                                                                                     |
| -------- | ------------------------------------------------------------------------------------------------- | ------------------ | ------------------------------------------------------------------------------------------------------------------------------------------- |
| `type`   | [?Components\UpdateMediaMp4SupportType](../../Models/Components/UpdateMediaMp4SupportType.md)     | :heavy_minus_sign: | The MP4 rendition type. `capped_4k` is a downloadable MP4 video capped at 4K resolution, `audioOnly` is a downloadable m4a audio-only file. |
| `status` | [?Components\UpdateMediaMp4SupportStatus](../../Models/Components/UpdateMediaMp4SupportStatus.md) | :heavy_minus_sign: | Generation status of this MP4 rendition.                                                                                                    |
| `height` | *?int*                                                                                            | :heavy_minus_sign: | Pixel height of the rendition. Omitted for the `audioOnly` type.                                                                            |
| `width`  | *?int*                                                                                            | :heavy_minus_sign: | Pixel width of the rendition. Omitted for the `audioOnly` type.                                                                             |
| `ext`    | [?Components\UpdateMediaMp4SupportExt](../../Models/Components/UpdateMediaMp4SupportExt.md)       | :heavy_minus_sign: | File extension of the downloadable rendition.                                                                                               |
