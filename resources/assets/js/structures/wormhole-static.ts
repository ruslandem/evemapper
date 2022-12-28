import { StyleValue } from "vue";

export interface WormholeStatic {
  hole: string,
  id: number,
  inClass: number,
  massRegeneration?: number,
  maxJumpMass?: number,
  maxStableMass?: number,
  maxStableTime?: number,
  name: string;
  color: StyleValue | undefined;
}
