import { BlueprintMaterial } from './blueprint-material';
import { BlueprintProduct } from './blueprint-product';

export interface Blueprint {
  typeID: number;
  typeName: string;
  groupID: number;
  volume: number;
  materials?: BlueprintMaterial[];
  products?: BlueprintProduct[];
  totals?: {
    materialsCosts: number;
    productsCosts: number;
  };
}
