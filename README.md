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
        address_id: int [address, city, countryCode, postalCode]
        coordinates: point
        uuid: string
        name: string
        origin: string
        #directions: [da, en]
        operator: [clever, eon]
        is_roaming_allowed: bool
        is_public_visiable: bool
    relationships:
        chargers: hasMany
        address: hasOne
        images: hasMany
        operator: belongsTo
        openingTimes: hasMany

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
