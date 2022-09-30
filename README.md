# clever.nemt.link
Version 2 of clever reminder


# Models
```yaml

User:
    attributes:
        id: int
        username: string
        email: string
        password: string
        telegram_chat_id: string
    relationships:
        subscriptions: hasMany

Subscription:
    attributes:
        id: int
        # location_id: int FK
        charger_id: int FK
        user_id: int FK
    relationships:
        # location: belongsTo
        charger: belongsTo
        user:  belongsTo

Company:
    attributes:
        id: int
        name: varchar
    relationships:
        locations: hasMany
        chargers: hasManyThroguh Location

Location:
    attributes:
        id: int
        provider: [clever, eon]
    relationships:
        chargers: hasMany

Charger:
    attributes:
        id: int
        type: [type_2, CHAdeMO, CCS]
        available: int
        faulty: int
        total: int
    relationships:
        location: belongsTo
```
