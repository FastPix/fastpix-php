# CreateMediaPlaybackIdAccessRestrictions


## Fields

| Field                                                                                 | Type                                                                                  | Required                                                                              | Description                                                                           |
| ------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------- |
| `domains`                                                                             | [?Components\DomainRestrictions](../../Models/Components/DomainRestrictions.md)       | :heavy_minus_sign:                                                                    | Restrictions based on the originating domain of a request                             |
| `userAgents`                                                                          | [?Components\UserAgentRestrictions](../../Models/Components/UserAgentRestrictions.md) | :heavy_minus_sign:                                                                    | Restrictions based on the user agent                                                  |