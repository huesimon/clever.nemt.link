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
        
Company:
    attributes:
        id: int
        name: varchar
    relationships:
        locations: hasMany
        #chargers: hasManyThroguh Location

Location:
    attributes:
        id: int
        external_id: string
        # TODO: address_id: int [address, city, countryCode, postalCode]
        name: string
        origin: string
        coordinates: 
        company_id: [clever, eon]
        is_roaming_allowed: bool
        is_public_visiable: bool
    relationships:
        chargers: hasMany
        subscribers: belongsToMany(User)
        #address: hasOne
        #images: hasMany
        #operator: belongsTo
        #openingTimes: hasMany

Charger:
    attributes:
        id: int
        location_id: fk
        evse_id: string
        status: string
        balance: string
        connector_id: string
        max_current_amp: int
        plug_type: string
        power_type: string
        speed: string
    relationships:
        location: belongsTo
    scopes:
        available: status=Available && connector_id != null
        plugType($type): plug_type=$type
        
LocationUser (subscribtions to a location):
    attributes: 
        location_id: fk
        user_id: fk
    relationships:
        user: belongsTo
        location: belongsTo
```
