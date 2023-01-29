<template>
  <div class="scrollable h-100 p-1">
    <div class="w-100 py-3">
      <button @click.prevent="expanded = !expanded" class="button">
        {{ viewToggleButtonName }}
      </button>
    </div>
    <div
      v-for="(bp, index) in props.blueprints"
      :key="bp.typeID"
      class="p-2 mb-1 is-dark-1"
    >
      <div class="columns">
        <div class="column">
          <h4 class="title is-4 my-1">{{ bp.typeName }}</h4>
        </div>
        <div class="column has-text-right">
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
              <tr class="has-background-dark">
                <th class="is-one-half">Item</th>
                <th class="has-text-right">Qty</th>
                <th class="has-text-right">Price</th>
                <th class="has-text-right">Cost</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="m in bp.materials" :key="m.materialTypeID">
                <td>{{ m.material_type.typeName }}</td>
                <td class="has-text-right">
                  {{ m.quantity?.toLocaleString() }}
                </td>
                <td class="has-text-right">
                  {{ m.price?.toLocaleString() }}
                </td>
                <td class="has-text-right">
                  {{ m.price ? (m.quantity * m.price).toLocaleString() : null }}
                </td>
              </tr>
              <tr class="has-background-dark">
                <td colspan="4" class="has-text-right has-text-weight-bold">
                  {{ bp.totals?.materialsCosts?.toLocaleString() }}
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
              <tr v-for="p in bp.products" :key="p.productTypeID">
                <td>{{ p.product_type.typeName }}</td>
                <td class="has-text-right">{{ p.quantity }}</td>
                <td class="has-text-right">
                  {{ p.price?.toLocaleString() }}
                </td>
                <td class="has-text-right">
                  {{ p.price ? (p.quantity * p.price).toLocaleString() : null }}
                </td>
              </tr>
              <tr class="has-background-dark">
                <td colspan="4" class="has-text-right has-text-weight-bold">
                  {{ bp.totals?.productsCosts?.toLocaleString() }}
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
