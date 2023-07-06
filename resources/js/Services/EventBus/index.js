import { reactive, provide, inject } from 'vue';

const eventBus = reactive({
  events: {},
  $emit(event, ...args) {
    if (this.events[event]) {
      this.events[event].forEach(callback => callback(...args));
    }
  },
  $on(event, callback) {
    if (!this.events[event]) {
      this.events[event] = [];
    }
    this.events[event].push(callback);
  },
});

export function provideEventBus() {
  provide('eventBus', eventBus);
}

export function useEventBus() {
  const eventBus = inject('eventBus');
  if (!eventBus) {
    throw new Error('Event bus not provided');
  }
  return eventBus;
}
