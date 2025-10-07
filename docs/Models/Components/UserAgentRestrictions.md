# UserAgentRestrictions

Restrictions based on the user agent


## Fields

| Field                                                               | Type                                                                | Required                                                            | Description                                                         |
| ------------------------------------------------------------------- | ------------------------------------------------------------------- | ------------------------------------------------------------------- | ------------------------------------------------------------------- |
| `defaultPolicy`                                                     | [?Components\PolicyAction](../../Models/Components/PolicyAction.md) | :heavy_minus_sign:                                                  | Policy action type                                                  |
| `allow`                                                             | array<*string*>                                                     | :heavy_minus_sign:                                                  | A list of user agents that are explicitly allowed access            |
| `deny`                                                              | array<*string*>                                                     | :heavy_minus_sign:                                                  | A list of user agents that are explicitly denied access             |