<template>
  <div class="scrollable h-100 p-1">
    <div class="w-100 py-3">
      <button @click.prevent="expanded = !expanded" class="button">
        {{ viewToggleButtonName }}
      </button>
    </div>
    <div
      v-for="(bp, index) in props.blueprints"
      :key="bp.typeId"
      class="p-2 mb-1 is-dark-1"
    >
      <div class="columns">
        <div class="column">
          <h4 class="title is-4 my-1">{{ bp.name }}</h4>
        </div>
        <div class="column is-one-fifth has-text-right">
          <h5
            v-if="profit"
            :class="{
              'has-text-success': profit[index] > 0,
              'has-text-warning': profit[index] < 0
            }"
            class="title is-5"
          >
            {{ profit[index]?.toLocaleString() }}
            <small class="is-size-6 has-text-light">isk</small>
          </h5>
        </div>
      </div>

      <div class="info" v-if="expanded">
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
                <td class="has-text-right">
                  {{ m.quantity?.toLocaleString() }}
                </td>
                <td class="has-text-right">
                  {{ m.sellPrice?.toLocaleString() }}
                </td>
                <td class="has-text-right">
                  {{
                    m.sellPrice
                      ? (m.quantity * m.sellPrice).toLocaleString()
                      : null
                  }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div>
          <h6 class="title is-6 mb-1 has-text-warning">Products</h6>
          <table class="products table is-narrow is-fullwidth is-size-7">
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
                    p.sellPrice
                      ? (p.quantity * p.sellPrice).toLocaleString()
                      : null
                  }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { getProfit } from '@/services/blueprints';
import { Blueprint } from '@/structures/blueprint';
import { watch, ref, computed } from 'vue';

interface Props {
  blueprints: Blueprint[];
}

const props = defineProps<Props>();
const profit = ref<number[]>();
const expanded = ref(false);
const viewToggleButtonName = computed(() => {
  return expanded.value ? 'compact' : 'detailed';
});

watch(props, async () => {
  if (props.blueprints && props.blueprints.length > 0) {
    profit.value = [];
    props.blueprints?.forEach(async (blueprint, index) => {
      profit.value?.push(await getProfit(blueprint));
    });
  }
});
</script>
