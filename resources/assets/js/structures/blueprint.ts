import { BlueprintMaterial } from "./blueprint-material";

export interface Blueprint {
  typeId: number;
  name: string;
  products?: BlueprintMaterial[];
  materials?: BlueprintMaterial[];
}


