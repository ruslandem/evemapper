import { Blueprint } from '@/structures/blueprint';

export const getProfit = async (blueprint: Blueprint): Promise<number> => {
  if (
    blueprint.totals?.productsCosts === undefined ||
    blueprint.totals?.materialsCosts === undefined
  ) {
    return 0;
  }

  return blueprint.totals?.productsCosts - blueprint.totals?.materialsCosts;
};
