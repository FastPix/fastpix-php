# CreatePlaylistRequestMetadata

Required when playlist type is smart - media created between startDate and endDate of createdDate will be add, similarily updatedDate (Optional)


## Fields

| Field                                                         | Type                                                          | Required                                                      | Description                                                   |
| ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- |
| `createdDate`                                                 | [?Components\DateRange](../../Models/Components/DateRange.md) | :heavy_minus_sign:                                            | Date range with start and end dates.                          |
| `updatedDate`                                                 | [?Components\DateRange](../../Models/Components/DateRange.md) | :heavy_minus_sign:                                            | Date range with start and end dates.                          |