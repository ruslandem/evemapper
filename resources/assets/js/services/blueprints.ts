import { Blueprint } from '@/structures/blueprint';

export const getProfit = async (blueprint: Blueprint): Promise<number> => {
  let result: Blueprint = blueprint;
  let profit: number = 0;

  result.materials?.forEach((item) => {
    if (profit !== undefined) {
      profit -= item.sellPrice * item.quantity;
    }
  });

  result.products?.forEach((item) => {
    if (profit !== undefined) {
      profit += item.sellPrice * item.quantity;
    }
  });

  return profit;
};
