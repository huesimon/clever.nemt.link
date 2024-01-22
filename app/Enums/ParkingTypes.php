<?php

namespace App\Enums;

enum ParkingTypes: string
{
    // case __default = 'All';
    case ParkingLot = 'ParkingLot';
    case ParkingGarage = 'ParkingGarage';
    case OnStreet = 'OnStreet';
    case UndergroundGarage = 'UndergroundGarage';
    case AlongMotorway = 'AlongMotorway';
    case OnDriveWay = 'OnDriveWay';

    public function label()
    {
        return match ($this) {
            self::ParkingLot => 'Parking Lot',
            self::ParkingGarage => 'Parking Garage',
            self::OnStreet => 'On Street',
            self::UndergroundGarage => 'Underground Garage',
            self::AlongMotorway => 'Along Motorway',
            self::OnDriveWay => 'On Drive Way',
        };
    }
}
