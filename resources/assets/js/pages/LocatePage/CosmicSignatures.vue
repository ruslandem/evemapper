<template>
  <div class="is-dark-1 p-4 my-1 has-text-centered">
    <h5 class="title">Cosmic Signatures</h5>

    <div class="table-container mb-6">
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
          <tr v-for="signature in signatures" :key="signature.id">
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
                  deleteSignatureClick(
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
        <a
          href="#"
          class="button mx-1"
          @click.prevent="updateSignatureClick(false)"
          >update</a
        >
        <a
          href="#"
          class="button mx-1"
          @click.prevent="updateSignatureClick(true)"
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
import { ref, watch } from 'vue';
import { toast } from 'bulma-toast';
import { Signature } from '@/structures/signature';
import { getRelativeTime, getCosmicSignatureIcon } from '@/services/utils';
import {
  fetchSignatures,
  updateSignatures,
  deleteSignature
} from '@/services/api';

interface Props {
  systemName?: string;
}

const props = defineProps<Props>();
const signatures = ref<Signature[]>([]);

watch(props, async () => {
  if (props.systemName) {
    signatures.value = await fetchSignatures(props.systemName);
  }
});

const updateSignatureClick = async (replace: boolean): Promise<void> => {
  const clipboardText = await navigator.clipboard.readText();

  if (props.systemName && clipboardText.length > 0) {
    const updated: string | null = await updateSignatures(
      props.systemName,
      clipboardText,
      replace
    );

    if (updated === null) {
      signatures.value = await fetchSignatures(props.systemName);
      toast({
        message: 'Signatures updated',
        type: 'is-success'
      });
    } else {
      toast({
        message: updated,
        type: 'is-danger'
      });
    }
  }
};

const deleteSignatureClick = async (
  id: string,
  solarSystemName: string
): Promise<void> => {
  const deleted = await deleteSignature(id, solarSystemName);
  if (deleted) {
    signatures.value = await fetchSignatures(solarSystemName);
    toast({
      message: `${id} signatures deleted`,
      type: 'is-success'
    });
  }
};
</script>
