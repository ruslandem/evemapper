<template>
  <div
    v-for="(bp, index) in props.blueprints"
    :key="bp.typeId"
    class="p-4 mb-4 is-dark-1"
  >
    <h4 class="title is-4 my-1">{{ bp.name }}</h4>
    <h5
      v-if="profit"
      :class="{
        'has-text-success': profit[index] > 0,
        'has-text-warning': profit[index] < 0
      }"
      class="title is-5"
    >
      {{ profit[index]?.toLocaleString() }}
      <sup v-if="profit[index] >= 0">profit</sup>
      <sup v-else>loss</sup>
    </h5>

    <div>
      <h6 class="title is-6 mb-1 has-text-warning">Materials</h6>
      <table class="materials table is-narrow is-fullwidth is-size-7 mb-3">
        <thead>
          <tr>
            <th class="is-one-half">Item</th>
            <th class="has-text-right">Qty</th>
            <th class="has-text-right">Price</th>
            <th class="has-text-right">Cost</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="m in bp.materials" :key="m.typeId">
            <td>{{ m.name }}</td>
            <td class="has-text-right">{{ m.quantity?.toLocaleString() }}</td>
            <td class="has-text-right">
              {{ m.sellPrice?.toLocaleString() }}
            </td>
            <td class="has-text-right">
              {{
                m.sellPrice ? (m.quantity * m.sellPrice).toLocaleString() : null
              }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div>
      <h6 class="title is-6 mb-1 has-text-warning">Products</h6>
      <table class="products table is-narrow is-fullwidth is-size-7 mb-3">
        <thead>
          <tr>
            <th class="is-one-half">Item</th>
            <th class="has-text-right">Qty</th>
            <th class="has-text-right">Price</th>
            <th class="has-text-right">Cost</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="p in bp.products" :key="p.typeId">
            <td>{{ p.name }}</td>
            <td class="has-text-right">{{ p.quantity }}</td>
            <td class="has-text-right">
              {{ p.sellPrice?.toLocaleString() }}
            </td>
            <td class="has-text-right">
              {{
                p.sellPrice ? (p.quantity * p.sellPrice).toLocaleString() : null
              }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { getProfit } from '@/services/blueprints';
import { Blueprint } from '@/structures/blueprint';
import { watch, ref, reactive } from 'vue';

interface Props {
  blueprints: Blueprint[];
}

const props = defineProps<Props>();
const profit = ref<number[]>();

watch(props, async () => {
  if (props.blueprints && props.blueprints.length > 0) {
    profit.value = [];
    props.blueprints?.forEach(async (blueprint, index) => {
      profit.value?.push(await getProfit(blueprint));
    });
  }
});
</script>
