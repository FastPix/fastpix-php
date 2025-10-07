# GetDataViewlistCurrentViewsGetTimeseriesViewsData


## Fields

| Field                                                         | Type                                                          | Required                                                      | Description                                                   |
| ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- |
| `intervalTime`                                                | [\DateTime](https://www.php.net/manual/en/class.datetime.php) | :heavy_minus_sign:                                            | The timestamp for the interval (ISO 8601 format).             |
| `metricValue`                                                 | *?int*                                                        | :heavy_minus_sign:                                            | Reserved for future metric values (currently null).           |
| `numberOfViews`                                               | *?int*                                                        | :heavy_minus_sign:                                            | Number of concurrent viewers at the given interval.           |