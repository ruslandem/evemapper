import { StyleValue } from "vue";
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
    ? { color: "#FF8000" }
    : { color: "#FF0000" };
};

export const getWormholeStaticColor = (
  inClass: number
): StyleValue | undefined => {
  let result: StyleValue | undefined = { color: "#579AED" };
  switch (inClass) {
    case 7:
      result = { color: "#57EDAA" };
      break;
    case 8:
      result = { color: "#FF8000" };
      break;
    case 9:
      result = { color: "#FF0000" };
      break;
    case 12:
      result = { color: "#900C3F" };
      break;
  }
  return result;
};

export const getWormholeStaticType = (inClass: number): string => {
  switch (inClass) {
    case 7:
      return "High";
    case 8:
      return "Low";
    case 9:
      return "Null";
    case 12:
      return "Thera";
  }
  return "C" + inClass.toString();
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
      return "c-icon-data-site";
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
  let headers: { [k: string]: any } = {};
  const token: HTMLMetaElement | null = document.head.querySelector(
    'meta[name="csrf-token"]'
  );
  if (token) {
    headers["X-CSRF-TOKEN"] = token.content;
  }
  const config = {
    headers: headers,
  };

  return { ...config, ...options };
};
/**
 * Get value of meta tag content or null if tag is not found.
 * @param  {string} name - Meta tag name
 * @returns string | null
 */
export const getMetaTagContent = (name: string): string | null => {
  const element: HTMLElement | null = document.querySelector(
    `meta[name=\"${name}\"]`
  );

  if (element instanceof HTMLMetaElement) {
    return element.content;
  }

  return null;
};
