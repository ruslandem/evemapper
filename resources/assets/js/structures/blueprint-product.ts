export interface BlueprintProduct {
    activityID: number;
    productTypeID: number;
    quantity: number;
    price: number;
    product_type: {
      typeID: number;
      groupID: number;
      typeName: string;
      mass: number;
      volume: number;
      capacity: number;
    };
  }
  