<template>
  <div class="is-dark-1 p-4 my-1 has-text-centered">
    <h5 class="title">Cosmic Signatures</h5>

    <div class="table-container">
      <table class="table is-fullwidth is-bordered is-size-7">
        <thead>
          <tr class="has-background-dark">
            <th>ID</th>
            <th>Group</th>
            <th>Name</th>
            <th>Created</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="signature in signatures" class="">
            <td>{{ signature.signatureId }}</td>
            <td>
              <i
                :class="`c-icon ${getCosmicSignatureIcon(signature.groupName)}`"
              ></i>
              {{ signature.groupName }}
            </td>
            <td>{{ signature.signatureName }}</td>
            <td>{{ getRelativeTime(signature.created_at) }}</td>
            <td>
              <button
                class="delete"
                @click="
                  deleteSignature(
                    signature.signatureId,
                    signature.solarSystemName
                  )
                "
              ></button>
            </td>
          </tr>
        </tbody>
      </table>

      <div>
        <a href="#" class="button mx-1" @click.prevent="updateSignatures(false)"
          >update</a
        >
        <a href="#" class="button mx-1" @click.prevent="updateSignatures(true)"
          >replace</a
        >
      </div>
    </div>
  </div>
</template>

<style scoped>
td {
  vertical-align: middle !important;
}
td:first-child,
td:nth-child(4),
td:last-child {
  width: 1%;
  white-space: nowrap;
}
</style>

<script setup lang="ts">
/**
 * Imports
 */
import { Signature } from "@/structures/Signature";
import {
  getRelativeTime,
  getCosmicSignatureIcon,
  getAxiosPostConfig,
} from "@/services/utils";
import axios from "axios";
import { toast } from "bulma-toast";
/**
 * Props
 */
const props = defineProps({
  signatures: Array<Signature>,
  systemName: String,
});
/**
 * Emits
 */
const emit = defineEmits(["delete-signature", "update-signatures"]);

/**
 * Emits event to delete a signature.
 * @param {string} id - Signature Id.
 * @param {string} systemName  - Solar system name.
 * @emits delete-signature
 */
const deleteSignature = (id: string, systemName: string): void => {
  emit("delete-signature", id, systemName);
};

/**
 * Handle `update` and  `replace` button click events.
 * Uses clipboard text to update signatures list.
 * @param {Boolean} replace - Replace signatures trigger.
 * @emits update-signatures
 */
const updateSignatures = (replace: Boolean): void => {
  navigator.clipboard.readText().then((value) => {
    axios
      .post(
        "/api/updateSignatures",
        {
          solarSystemName: props.systemName,
          text: value,
          replace: replace,
        },
        getAxiosPostConfig()
      )
      .then(() => {
        emit("update-signatures");
        toast({
          message: "Signatures updated",
          type: "is-success",
        });
      });
  });
};
</script>
