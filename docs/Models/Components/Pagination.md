# Pagination

Pagination organizes content into pages for better readability and navigation.


## Fields

| Field                                                                            | Type                                                                             | Required                                                                         | Description                                                                      | Example                                                                          |
| -------------------------------------------------------------------------------- | -------------------------------------------------------------------------------- | -------------------------------------------------------------------------------- | -------------------------------------------------------------------------------- | -------------------------------------------------------------------------------- |
| `totalRecords`                                                                   | *?int*                                                                           | :heavy_minus_sign:                                                               | It gives the total number of media assets that are accessible overall.           | 100                                                                              |
| `currentOffset`                                                                  | *?int*                                                                           | :heavy_minus_sign:                                                               | Offset determines the current point for data retrieval within a paginated list.  | 1                                                                                |
| `offsetCount`                                                                    | *?int*                                                                           | :heavy_minus_sign:                                                               | The offset count is expressed as total records by limit                          | 10                                                                               |