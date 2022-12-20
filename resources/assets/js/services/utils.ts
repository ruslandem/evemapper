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

export const getCosmicSignatureIcon = (groupName: string | null): string => {
  switch (groupName) {
    case "Data Site":
      return 'c-icon-data-site';
    case "Relic Site":
      return "c-icon-relic-site";
    case "Combat Site":
      return "c-icon-combat-site";
    case "Wormhole":
      return "c-icon-wormhole";
    case "Gas Site":
      return "c-icon-gas-site";
  }
  // transparent image
  return "data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==";
};
/**
 * Get congiuration for axios post, put, delete requests.
 * @param  {object} options
 * @returns object
 */
export const getAxiosPostConfig = (options: object = {}): object => {
  let headers: {[k: string]: any} = {};
  const token: HTMLMetaElement|null = document.head.querySelector('meta[name="csrf-token"]');
  if (token) {
    headers["X-CSRF-TOKEN"] = token.content;
  }
  const config = {
    headers: headers
  };

  return {...config, ...options};
}