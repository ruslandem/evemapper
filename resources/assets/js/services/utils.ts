import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
dayjs.extend(relativeTime);

/**
 * Get style color for solar system security status.
 * @param  {number|undefined} value
 * @returns {any}
 */
export const getSecurityStatusStyle = (value: number | undefined): any => {
  if (value === undefined) {
    return { color: "#FF0000" };
  }

  return value >= 0.8
    ? { color: "#00BFFF" }
    : value >= 0.6
    ? { color: "#57EDAA" }
    : value >= 0.5
    ? { color: "#FFD700" }
    : value >= 0
    ? { color: "#FF8C00" }
    : { color: "#FF0000" };
};

/**
 * Return date and time in human readable format.
 * @param  {string} time
 * @returns string
 */
export const getRelativeTime = (time: string): string => {
  return dayjs().to(dayjs(time));
};
