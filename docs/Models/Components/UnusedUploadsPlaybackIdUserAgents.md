# UnusedUploadsPlaybackIdUserAgents

Restrictions based on the user agent (which is typically a string sent by browsers or bots identifying themselves).


## Fields

| Field                                                                   | Type                                                                    | Required                                                                | Description                                                             |
| ----------------------------------------------------------------------- | ----------------------------------------------------------------------- | ----------------------------------------------------------------------- | ----------------------------------------------------------------------- |
| `defaultPolicy`                                                         | [?Components\PolicyAction](../../Models/Components/PolicyAction.md)     | :heavy_minus_sign:                                                      | Policy action type                                                      |
| `allow`                                                                 | array<*string*>                                                         | :heavy_minus_sign:                                                      | A list of specific user agents that are allowed to access the resource. |
| `deny`                                                                  | array<*string*>                                                         | :heavy_minus_sign:                                                      | A list of specific user agents that are blocked.                        |