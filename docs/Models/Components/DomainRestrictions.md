# DomainRestrictions

Restrictions based on the originating domain of a request


## Fields

| Field                                                                 | Type                                                                  | Required                                                              | Description                                                           |
| --------------------------------------------------------------------- | --------------------------------------------------------------------- | --------------------------------------------------------------------- | --------------------------------------------------------------------- |
| `defaultPolicy`                                                       | [?Components\PolicyAction](../../Models/Components/PolicyAction.md)   | :heavy_minus_sign:                                                    | Policy action type                                                    |
| `allow`                                                               | array<*string*>                                                       | :heavy_minus_sign:                                                    | A list of domain names or patterns that are explicitly allowed access |
| `deny`                                                                | array<*string*>                                                       | :heavy_minus_sign:                                                    | A list of domain names or patterns that are explicitly denied access  |