export interface SolarSystem {
  solarSystemID: number;
  solarSystemName: string;
  regionName: string;
  constellationName: string;
  security: number;
  rats: string;
  wormholeClass?: string;
  wormholeStatics?: string[];
}
