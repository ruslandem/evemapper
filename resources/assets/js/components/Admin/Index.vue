<template>
  <div class="box my-3 is-dark-1 p-5">
    <h3 class="title">Statistics</h3>

    <table class="table is-bordered has-background-light has-text-black w-100">
      <thead class="has-background-primary">
        <tr>
          <th>Character ID</th>
          <th>Character Name</th>
          <th>Systems</th>
          <th>Signatures</th>
          <th>Recorded</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in statistics">
          <td>{{ item.characterId }}</td>
          <td>{{ item.characterName }}</td>
          <td>{{ item.solarSystemsCount }}</td>
          <td>{{ item.signaturesCount }}</td>
          <td>{{ getRelativeTime(item.updatedAt) }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
import axios from "axios";
import { ref, onMounted } from "vue";
import { CharacterStatistics } from "@/structures/CharacterStatistics";
import { getRelativeTime } from "@/services/utils";

const statistics = ref([{}] as CharacterStatistics[]);

onMounted(() => {
  axios.get("/api/admin/getStatistics").then((response) => {
    statistics.value = response.data;
  });
});
</script>
