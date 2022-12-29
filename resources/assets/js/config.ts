const _gTagId: string = 'G-GPRF1TNFPB';

export const gTagId = (): string | null => {
  return localStorage.getItem('GlowCookies') === '1' ? _gTagId : null;
};
