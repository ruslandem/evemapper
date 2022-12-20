import { AuthData } from "@/structures/AuthData";

const getMetaTagContent = (name: string): string | null => {
  const element: HTMLElement | null = document.querySelector(
    `meta[name=\"${name}\"]`
  );

  if (element instanceof HTMLMetaElement) {
    return element.content;
  }

  return null;
};

const toNumberOrNull = (value: string | null): number | null => {
  if (value !== null) {
    return parseInt(value);
  }
  return null;
};

export const authData: AuthData = {
  characterId: toNumberOrNull(getMetaTagContent("character-id")),
  characterName: getMetaTagContent("character-name"),
};

export const isAuthenticated = (): boolean => {
  return authData.characterId !== null;
};
