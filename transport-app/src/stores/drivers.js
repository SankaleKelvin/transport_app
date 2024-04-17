// drivers.js
import { defineStore } from 'pinia';
import api from '../services/api';

export const useDriversStore = defineStore({
  id: 'drivers',
  state: () => ({
    drivers: [],
    snackbarCreate: false,
    snackbarUpdate: false,
    snackbarDelete: false,
    dialog: false,
    dialogDelete: false,
    editedIndex: -1,
    editedItem: {
      id: '',
      driver_name: '',
    },
    defaultItem: {
      id: '',
      driver_name: '',
    },
    itemToDelete: '',
  }),

  actions: {
    async fetchDrivers() {
      try {
        const response = await api.get('driver');
        this.drivers = response.data;
      } catch (error) {
        console.error('Error fetching drivers', error);
      }
    },

    async createDriver(driver) {
      try {
        const response = await api.post('driver', driver);
        this.drivers.push(response.data);
        this.snackbarCreate = true;        
      } catch (error) {
        console.error('Error creating driver', error);
      }
    },

    async updateDriver(driver) {
      try {
        await api.put(`driver/${driver.id}`, driver);
        const index = this.drivers.findIndex((s) => s.id === driver.id);
        if (index !== -1) {
          this.drivers[index] = driver;
        }
        this.snackbarUpdate = true;        
      } catch (error) {
        console.error('Error updating driver', error);
      }
    },

    deleteDriver(item) {
      this.editedIndex = item;
      this.itemToDelete = item;
      this.dialogDelete = true;
    },

    async deleteDriverConfirm() {
      try {
        await api.delete(`driver/${this.itemToDelete}`);
        this.drivers = this.drivers.filter((s) => s.id !== this.itemToDelete);        
      } catch (error) {
        console.error('Error deleting driver', error);
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
        const response = await api.get(`driver/${item}`);
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
