import { defineStore } from "pinia";
import { CharacterInfo } from "@/structures/CharacterInfo";
import { getMetaTagContent } from "@/services/utils";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    character: null as CharacterInfo | null,
  }),

  getters: {
    /**
     * Returns true if user is authenticated.
     * @param  {} state
     * @returns boolean
     */
    isAuthenticated: (state): boolean => {
      return state.character !== null;
    },
  },
  actions: {
    /**
     * Get auth data from html meta tags.
     * @returns void
     */
    getAuthentication: function (): void {
      const id: string | null = getMetaTagContent("character-id");
      const name: string | null = getMetaTagContent("character-name");

      this.character =
        id !== null && name !== null ? { id: parseInt(id), name: name } : null;
    },
  },
});
