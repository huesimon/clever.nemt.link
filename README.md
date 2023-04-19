# clever.nemt.link
Version 2 of clever reminder



# Endpoints

`GET: api/locations` Get all locations
```json
[
    {
        "external_id": "0005b2ef-5dbf-ea11-a812-000d3ad97943",
        "company_id": 1,
        "name": "P-plads Teglgårdssøen - Hillerød",
        "origin": "clever",
        "is_roaming_allowed": 1,
        "is_public_visible": "Always",
        "coordinates": "55.923953,12.311272",
        "created_at": "2023-04-03T15:37:50.000000Z",
        "updated_at": "2023-04-04T09:14:16.000000Z"
    },
    {...},
    {...},
]
```

`GET: api/locations/{location:external_id}` Get a specific location
```json
{
    "external_id": "0005b2ef-5dbf-ea11-a812-000d3ad97943",
    "company_id": 1,
    "name": "P-plads Teglgårdssøen - Hillerød",
    "origin": "clever",
    "is_roaming_allowed": 1,
    "is_public_visible": "Always",
    "coordinates": "55.923953,12.311272",
    "created_at": "2023-04-03T15:37:50.000000Z",
    "updated_at": "2023-04-04T09:14:16.000000Z",
    "chargers": [
        {
        "evse_id": "DK*CLE*E11090*1-1",
        "location_external_id": "0005b2ef-5dbf-ea11-a812-000d3ad97943",
        "evse_connector_id": "DK*CLE*E11090*1-1",
        "status": "Available",
        "balance": null,
        "connector_id": "1",
        "max_current_amp": 32,
        "max_power_kw": 22.08,
        "plug_type": "Type2",
        "power_type": null,
        "speed": "Standard",
        "created_at": "2023-04-04T09:14:17.000000Z",
        "updated_at": "2023-04-04T09:14:17.000000Z"
        },
        {
        "evse_id": "DK*CLE*E11090*2-2",
        "location_external_id": "0005b2ef-5dbf-ea11-a812-000d3ad97943",
        "evse_connector_id": "DK*CLE*E11090*2-2",
        "status": "Available",
        "balance": null,
        "connector_id": "2",
        "max_current_amp": 32,
        "max_power_kw": 22.08,
        "plug_type": "Type2",
        "power_type": null,
        "speed": "Standard",
        "created_at": "2023-04-04T09:14:17.000000Z",
        "updated_at": "2023-04-04T09:14:17.000000Z"
        },
        {
        "evse_id": "DK*CLE*E11296*1-1",
        "location_external_id": "0005b2ef-5dbf-ea11-a812-000d3ad97943",
        "evse_connector_id": "DK*CLE*E11296*1-1",
        "status": "Available",
        "balance": null,
        "connector_id": "1",
        "max_current_amp": 16,
        "max_power_kw": 11.04,
        "plug_type": "Type2",
        "power_type": null,
        "speed": "Standard",
        "created_at": "2023-04-04T09:14:17.000000Z",
        "updated_at": "2023-04-04T09:14:17.000000Z"
        },
        {
        "evse_id": "DK*CLE*E11296*2-2",
        "location_external_id": "0005b2ef-5dbf-ea11-a812-000d3ad97943",
        "evse_connector_id": "DK*CLE*E11296*2-2",
        "status": "Available",
        "balance": null,
        "connector_id": "2",
        "max_current_amp": 16,
        "max_power_kw": 11.04,
        "plug_type": "Type2",
        "power_type": null,
        "speed": "Standard",
        "created_at": "2023-04-04T09:14:17.000000Z",
        "updated_at": "2023-04-04T09:14:17.000000Z"
        }
    ]
}
```


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
