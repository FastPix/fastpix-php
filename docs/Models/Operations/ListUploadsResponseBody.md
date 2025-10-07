# ListUploadsResponseBody

List of video media


## Fields

| Field                                                                          | Type                                                                           | Required                                                                       | Description                                                                    | Example                                                                        |
| ------------------------------------------------------------------------------ | ------------------------------------------------------------------------------ | ------------------------------------------------------------------------------ | ------------------------------------------------------------------------------ | ------------------------------------------------------------------------------ |
| `success`                                                                      | *?bool*                                                                        | :heavy_minus_sign:                                                             | Demonstrates whether the request is successful or not.                         | true                                                                           |
| `data`                                                                         | array<[Components\DirectUpload](../../Models/Components/DirectUpload.md)>      | :heavy_minus_sign:                                                             | Displays the result of the request.                                            |                                                                                |
| `pagination`                                                                   | [?Components\Pagination](../../Models/Components/Pagination.md)                | :heavy_minus_sign:                                                             | Pagination organizes content into pages for better readability and navigation. |                                                                                |