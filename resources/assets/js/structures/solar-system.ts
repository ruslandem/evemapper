import { WormholeStatic } from './wormhole-static';

export interface SolarSystem {
  solarSystemID: number;
  solarSystemName: string;
  region: {
    regionID: number;
    regionName: string;
    rats?: {
      rats: string;
    };
  };
  constellation: {
    constellationID: number;
    constellationName: string;
  };
  security: number;
  rats: string;
  wormhole?: {
    id: number;
    system: string;
    class: number;
    star: string;
    planets: number;
    moons: number;
    effect?: string;
    statics?: [
      {
        hole: string;
        inClass: number;
        maxStableTime: number;
        maxStableMass: number;
        massRegeneration: number;
        maxJumpMass: number;
      }
    ];
  };
  jumps?: [
    {
      fromRegionID: number;
      fromConstellationID: number;
      fromSolarSystemID: number;
      toSolarSystemID: number;
      toConstellationID: number;
      toRegionID: number;
      to_solar_system: SolarSystem;
    }
  ];
}
