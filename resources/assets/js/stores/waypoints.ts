import { defineStore } from "pinia";

export const useWaypointsStore = defineStore("waypoints", {
  state: () => ({
    waypoints: [] as string[],
  }),
  actions: {
    add (waypoint: string): void {
      this.waypoints.push(waypoint);
    },
    remove (waypoint: string): void {
      const waypointIndex = this.waypoints.indexOf(waypoint);
      if (waypointIndex !== -1) {
        this.waypoints.splice(waypointIndex, 1);
      }
    },
    setOrigin (waypoint: string): void {
        const waypointIndex = this.waypoints.indexOf(waypoint);
        if (waypointIndex > 0) {
            this.waypoints[0] = this.waypoints.splice(waypointIndex, 1, this.waypoints[0])[0];
        }
    },
    setDestination (waypoint: string): void {
        const waypointIndex = this.waypoints.indexOf(waypoint);
        const destinationIndex = Object.keys(this.waypoints).length - 1;
        if (waypointIndex >= 0 && waypointIndex < destinationIndex) {
            this.waypoints[destinationIndex] = this.waypoints.splice(waypointIndex, 1, this.waypoints[destinationIndex])[0];
        }
    },
  },
});
