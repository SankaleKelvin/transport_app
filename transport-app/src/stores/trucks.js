// trucks.js
import { defineStore } from 'pinia';
import api from '../services/api';

export const useTrucksStore = defineStore({
  id: 'trucks',
  state: () => ({
    trucks: [],
    snackbarCreate: false,
    snackbarUpdate: false,
    snackbarDelete: false,
    dialog: false,
    dialogDelete: false,
    editedIndex: -1,
    editedItem: {
      id: '',
      truck_name: '',
      driver_id: '',
      image_path: '',
    },
    defaultItem: {
      id: '',
      truck_name: '',
      driver_id: '',
      image_path: ''
    },
    itemToDelete: '',
  }),

  actions: {
    async fetchTrucks() {
      try {
        const response = await api.get('truck');
        this.trucks = response.data;
      } catch (error) {
        console.error('Error fetching trucks', error);
      }
    },

    async createTruck(truck) {
      try {
        const response = await api.post('truck', truck);
        this.trucks.push(response.data);
        this.snackbarCreate = true;        
      } catch (error) {
        console.error('Error creating truck', error);
      }
    },

    async updateTruck(truck) {
      try {
        await api.put(`truck/${truck.id}`, truck);
        const index = this.trucks.findIndex((s) => s.id === truck.id);
        if (index !== -1) {
          this.trucks[index] = truck;
        }
        this.snackbarUpdate = true;        
      } catch (error) {
        console.error('Error updating truck', error);
      }
    },

    deleteTruck(item) {
      this.editedIndex = item;
      this.itemToDelete = item;
      this.dialogDelete = true;
    },

    async deleteTruckConfirm() {
      try {
        await api.delete(`truck/${this.itemToDelete}`);
        this.trucks = this.trucks.filter((s) => s.id !== this.itemToDelete);        
      } catch (error) {
        console.error('Error deleting truck', error);
      }
      this.closeDelete();
    },

    openDialog() {
        this.editedIndex = -1; 
        this.editedItem = { id: '', name: '' };    
        this.dialog = true;   
      },

    async editItem(item) {
      try {
        console.log("Item to edit is ", item);
        const response = await api.get(`truck/${item}`);
        this.editedIndex = item;
        this.editedItem = response.data;
        this.dialog = true;
      } catch (error) {
        console.error('Error fetching item for pre-update', error);
      }
    },

    close() {
      this.dialog = false;
    },
    
    closeDelete() {
      this.dialogDelete = false;
    },
    
    
  },
});
