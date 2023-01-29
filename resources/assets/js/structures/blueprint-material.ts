export interface BlueprintMaterial {
  activityID: number;
  materialTypeID: number;
  quantity: number;
  price: number;
  material_type: {
    typeID: number;
    groupID: number;
    typeName: string;
    mass: number;
    volume: number;
    capacity: number;
  };
}
