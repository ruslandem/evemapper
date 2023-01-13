<template>
  <div class="is-size-5 columns">
    <span class="column">{{ originSystem }} ... {{ destinationSystem }}</span>
    <span class="column has-text-right">
      <a
        @click.prevent="setWaypointAutopilot(destinationSystem)"
        href="#"
        title="Add waypoint to autopilot"
        class="has-text-right"
      >
        <fa-icon
          icon="fas fa-location"
          class="mx-1 has-text-warning fa-lg"
          mask="fas fa-circle"
          transform="shrink-2"
        />
      </a>
    </span>
  </div>
</template>

<script setup lang="ts">
import { addAutopilotWaypoint } from '@/services/api';
import { SolarSystem } from '@/structures/solar-system';
import { toast } from 'bulma-toast';
import { computed } from 'vue';

interface Props {
  route: SolarSystem[];
}

const props = defineProps<Props>();

const setWaypointAutopilot = async (name: string): Promise<void> => {
  if (await addAutopilotWaypoint(name)) {
    toast({
      message: `Waypoint added ${name}`,
      type: 'is-success'
    });
  }
};

const originSystem = computed<string>(() => {
  if (props.route.length > 0) {
    return props.route[0].solarSystemName;
  }
  return "";
});

const destinationSystem = computed<string>(() => {
  if (props.route.length > 0) {
    return props.route[props.route.length - 1].solarSystemName;
  }
  return "";
});

</script>
