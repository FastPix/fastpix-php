# BadRequestError

Displays details about the reasons behind the request's failure.


## Fields

| Field                                                         | Type                                                          | Required                                                      | Description                                                   | Example                                                       |
| ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- |
| `code`                                                        | *?float*                                                      | :heavy_minus_sign:                                            | Displays the error code indicating the type of the error.     | 400                                                           |
| `message`                                                     | *?string*                                                     | :heavy_minus_sign:                                            | A descriptive message providing more details for the error.   | Bad Request                                                   |
| `description`                                                 | *?string*                                                     | :heavy_minus_sign:                                            | A detailed explanation of the possible causes for the error.<br/> | trial plan limits reached. Please upgrade plan to continue    |