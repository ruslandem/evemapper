import { HubsJump } from '@/structures/hubj-jump';
import { Signature } from '@/structures/signature';
import { SolarSystem } from '@/structures/solar-system';
import { VisitedLocation } from '@/structures/visited-location';
import axios from 'axios';
import { getAxiosPostConfig } from './utils';
/**
 * Get solar system names by the search string.
 * @async
 * @param {string} search
 * @param {Function} loading
 * @returns Promise<string[]>
 */
export const fetchSolarSystems = async (
  search: string,
  loading: Function
): Promise<string[]> => {
  if (search.length > 1) {
    loading(true);
    const { data, status } = await axios.get<string[]>(
      `/api/getSolarSystems/${search}`
    );
    loading(false);

    if (status == 200) {
      return data;
    }
  }
  return [];
};

/**
 * Fetch current location of the character.
 * @async
 * @returns string
 */
export const fetchCurrentSystem = async () => {
  const { data, status } = await axios.get<string>('/api/getLocation');
  return status == 200 ? data : '';
};

/**
 * Add waypoint to the character autopilot.
 * @async
 * @param name
 * @returns Promise<boolean>
 */
export const addAutopilotWaypoint = async (name: string): Promise<boolean> => {
  const { status } = await axios.post('/api/addAutopilotWaypoint', {
    system: name
  });
  return status == 200;
};

/**
 * Fetch signatures for specific solar system and updates `signatures` object.
 * @async
 * @param systemName - Solar system name.
 */
export const fetchSignatures = async (
  systemName: string
): Promise<Signature[]> => {
  const { data, status } = await axios.get<{ data: Signature[] }>(
    `/api/getSignatures/${systemName}`
  );

  return status == 200 && data.data.length > 0 ? data.data : [];
};

export const fetchHistory = async (): Promise<VisitedLocation[]> => {
  const { data, status } = await axios.get<VisitedLocation[]>(
    `/api/getLocationsHistory`
  );
  return status == 200 ? data : [];
};

interface solarSystemInfo {
  system?: SolarSystem;
  jumps?: HubsJump;
}

export const fetchSolarSystemInfo = async (
  solarSystemName: string
): Promise<solarSystemInfo> => {
  const { data, status } = await axios.get<solarSystemInfo>(
    `/api/getSolarSystemInfo/${solarSystemName}`
  );

  return status == 200 ? data : {};
};

export const updateSignatures = async (
  solarSystemName: string,
  text: string,
  replace: boolean
): Promise<string | null> => {
  const { data, status } = await axios.post(
    '/api/updateSignatures',
    {
      solarSystemName,
      text,
      replace
    },
    getAxiosPostConfig()
  );

  if (status === 200) {
    return data.error;
  }

  return 'update error';
};

export const deleteSignature = async (
  id: string,
  systemName: string
): Promise<boolean> => {
  const { status } = await axios.post(
    '/api/deleteSignature',
    {
      id: id,
      systemName: systemName
    },
    getAxiosPostConfig()
  );
  return status === 200;
};
