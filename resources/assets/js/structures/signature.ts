export interface Signature {
  id: string;
  solarSystemName: string;
  signatureId: string;
  signatureName: string | null;
  groupName: string | null;
  external_link?: {
    name: string;
    url?: string;
    wiki_url?: string;
  };
  created_at: string;
  updated_at: string;
}
