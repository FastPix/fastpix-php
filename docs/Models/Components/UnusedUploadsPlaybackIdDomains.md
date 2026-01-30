# UnusedUploadsPlaybackIdDomains

Restrictions based on the originating domain of a request (for example, whether requests from certain websites must be allowed or blocked).


## Fields

| Field                                                                      | Type                                                                       | Required                                                                   | Description                                                                |
| -------------------------------------------------------------------------- | -------------------------------------------------------------------------- | -------------------------------------------------------------------------- | -------------------------------------------------------------------------- |
| `defaultPolicy`                                                            | [?Components\PolicyAction](../../Models/Components/PolicyAction.md)        | :heavy_minus_sign:                                                         | Policy action type                                                         |
| `allow`                                                                    | array<*string*>                                                            | :heavy_minus_sign:                                                         | A list of domains that are explicitly allowed access.                      |
| `deny`                                                                     | array<*string*>                                                            | :heavy_minus_sign:                                                         | A list of domains that are explicitly blocked from accessing the resource. |