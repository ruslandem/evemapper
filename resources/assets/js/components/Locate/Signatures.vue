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
                @click="deleteSignature(signature.signatureId, signature.solarSystemName)"
              ></button>
            </td>
          </tr>
        </tbody>
      </table>

      <div>
        <a id="updateSignatures" href="#" class="button mx-1">update</a>
        <a id="replaceSignatures" href="#" class="button mx-1">replace</a>
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
import { Signature } from "@/structures/Signature";
import { getRelativeTime, getCosmicSignatureIcon } from "@/services/utils";

defineProps({
  signatures: Array<Signature>,
});

const emit = defineEmits(["deleteSignature"]);

const deleteSignature = (id: string, systemName: string): void => {
  emit("deleteSignature", id, systemName);
};
</script>
