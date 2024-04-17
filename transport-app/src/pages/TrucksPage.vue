<template>
    <v-main>
        <v-snackbar v-model="snackbarCreate" :timeout="snackbarTimeout" :color="toolbarColor">
            Record has been created successfully.
            <v-btn text @click="snackbarCreate = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="snackbarUpdate" :timeout="snackbarTimeout" :color="toolbarColor">
            Record has been updated successfully.
            <v-btn text @click="snackbarUpdate = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="snackbarDelete" :timeout="snackbarTimeout" :color="toolbarColor">
            Record has been deleted successfully.
            <v-btn text @click="snackbarDelete = false">Close</v-btn>
        </v-snackbar>

        <v-data-table :headers="headers" :items="trucks" :sort-by="[{ key: 'id', order: 'asc' }]" class="elevation-1">
            <template v-slot:top>
                <v-toolbar flat :color="toolbarColor">
                    <v-toolbar-title>Truck</v-toolbar-title>
                    <v-divider class="mx-4" inset vertical></v-divider>
                    <v-spacer></v-spacer>
                    <v-dialog v-model="dialog" max-width="500px" @click:outside="close">
                        <template v-slot:activator="{ props }">
                            <v-btn color="dark" dark class="mb-2 fs-6 bg-light" v-bind="props" @click="openDialog">
                                New Truck
                            </v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="text-h5">{{ formTitle }}</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12" sm="12" md="12">
                                            <v-text-field v-model="editedItem.name" label="Truck name"
                                                :rules="inputRules"></v-text-field>
                                            <v-text-field v-model="editedItem.truck_id" label="Truck Id"
                                                :rules="inputRules"></v-text-field>
                                            <v-select v-model="editedItem.driver_id" label="Driver"
                                                :items="driverItem"></v-select>
                                            <v-file-input v-model="image" label="Upload Image" show-size counter="1"
                                                accept="image/*"></v-file-input>
                                            <v-img v-if="filePreview" :src="filePreview" alt="Selected Image" width="200"
                                                height="200"></v-img>
                                            <v-img v-else :src="getImageUrl(editedItem.image_path)" alt="Selected Image"
                                                width="200" height="200"></v-img>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue-darken-1" variant="text" @click="close">Cancel</v-btn>
                                <v-btn color="blue-darken-1" variant="text" @click="save">Save</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <v-dialog v-model="dialogDelete" max-width="500px" @click:outside="closeDelete">
                        <v-card>
                            <v-card-title class="text-h5">Are you sure you want to delete this truck?</v-card-title>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue-darken-1" variant="text" @click="closeDelete">Cancel</v-btn>
                                <v-btn color="blue-darken-1" variant="text" @click="deleteItemConfirm()">OK</v-btn>
                                <v-spacer></v-spacer>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
            </template>
            <template v-slot:[`item.image_path`]="{ item }">
                <v-img :src="getImageUrl(item.image_path)" alt="Truck Image" width="50" height="50"></v-img>
            </template>
            <template v-slot:[`item.actions`]="{ item }">
                <v-icon size="small" class="me-2" @click="editItem(item.driver_id)">
                    mdi-pencil
                </v-icon>
                <v-icon size="small" @click="deleteItem(item.key)">
                    mdi-delete
                </v-icon>
            </template>
            <template v-slot:no-data>
                <v-btn color="primary" @click="initialize">
                    Reset
                </v-btn>
            </template>
        </v-data-table>
    </v-main>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import { useTrucksStore } from '../stores/trucks';
import { useDriversStore } from '../stores/drivers';
import { useColorsStore } from '../stores/colors';

export default {
    setup() {
        const trucksStore = useTrucksStore();
        const driversStore = useDriversStore();

        const snackbarCreate = ref(false);
        const snackbarUpdate = ref(false);
        const snackbarDelete = ref(false);
        const toolbarColor = ref('info');
        const snackbarTimeout = 100;

        const footerColoring = useColorsStore();
        const headers = [
            { title: 'ID', key: 'id', align: 'start' },
            { title: 'Truck Name', align: 'start', sortable: true, key: 'truck_name' },
            { title: 'Driver', align: 'start', sortable: true, key: 'driver_id' },
            { title: 'Image', align: 'start', sortable: false, key: 'image_path' },
            { title: 'Actions', sortable: false, key: 'actions' },
        ];

        const trucks = computed(() => trucksStore.trucks);

        const image = ref(null);
        const filePreview = computed(() => {
            const file = image.value ? image.value[0] : null;
            console.log("Image value is ...", file);
            return image.value ? URL.createObjectURL(file) : null;
        });

        const getImageUrl = (imagePath) => {
            console.log("Image path is:", imagePath);
            return imagePath ? `http://127.0.0.1:8000/storage/${imagePath}` : null;
        };
        const driverItem = computed(() => {
            return driversStore.drivers.map(driver => (driver.name));
        });

        const editedItem = computed(() => trucksStore.editedItem);
        const formTitle = computed(() => (trucksStore.editedIndex === -1 ? 'New Truck' : 'Edit Truck'));

        const dialog = computed(() => trucksStore.dialog);
        const dialogDelete = computed(() => trucksStore.dialogDelete);

        async function save() {
            const selectedDriverName = editedItem.value.driver_id;
            const selectedDriver = driversStore.drivers.find(driver => driver.name === selectedDriverName);

            if (!selectedDriver) {
                console.error('Selected driver not found.');
                return;
            }

            trucksStore.editedItem.driver_id = selectedDriver.id;

            // Create FormData
            const formData = new FormData();
            // formData.append('id', editedItem.value.id);
            formData.append('name', editedItem.value.name);
            formData.append('truck_id', editedItem.value.truck_id);
            formData.append('driver_id', trucksStore.editedItem.driver_id);

            // Check if file is selected
            if (image.value) {
                formData.append('image', image.value[0] || 'unknownFileName');
            } else {
                formData.append('image', 'Unavailable!');
            }

            if (trucksStore.editedIndex > -1) {
                await trucksStore.updateTruck(formData);
                snackbarUpdate.value = true;
                toolbarColor.value = 'warning';
                footerColoring.setFooterColor('warning');
                setTimeout(() => {
                    snackbarCreate.value = false;
                    toolbarColor.value = 'info';
                    footerColoring.setFooterColor('info');                    
                    location.reload();
                }, snackbarTimeout);
            } else {
                await trucksStore.createTruck(formData);
                snackbarCreate.value = true;
                toolbarColor.value = 'success';
                footerColoring.setFooterColor('success');
                setTimeout(() => {
                    snackbarCreate.value = false;
                    toolbarColor.value = 'info';
                    footerColoring.setFooterColor('info');
                }, snackbarTimeout);
            }
            // window.location.reload();
            close();
        }


        function editItem(id) {
            console.log("edit number ", id);
            trucksStore.editItem(id);
        }

        function deleteItem(id) {
            trucksStore.deleteTruck(id);
        }

        function deleteItemConfirm() {
            trucksStore.deleteTruckConfirm();
            snackbarDelete.value = true;
            toolbarColor.value = 'danger';
            footerColoring.setFooterColor('danger');
            setTimeout(() => {
                snackbarCreate.value = false;
                toolbarColor.value = 'info';
                footerColoring.setFooterColor('info');
            }, snackbarTimeout);
        }

        function openDialog() {
            trucksStore.openDialog();
        }

        function close() {
            trucksStore.close();
        }

        function closeDelete() {
            trucksStore.closeDelete();
        }

        function initialize() {
            trucksStore.initialize();
        }

        // Fetch trucks when the component is mounted
        onMounted(() => {
            trucksStore.fetchTrucks();
            driversStore.fetchDrivers();
            console.log("Driver items:", driverItem.value);
        });

        return {
            snackbarCreate,
            snackbarUpdate,
            snackbarDelete,
            snackbarTimeout,
            toolbarColor,
            headers,
            trucks,
            editedItem,
            formTitle,
            dialog,
            dialogDelete,
            openDialog,
            editItem,
            deleteItem,
            deleteItemConfirm,
            close,
            closeDelete,
            initialize,
            save,
            driverItem,
            filePreview,
            getImageUrl,
            image,
        };
    },
};
</script>

<style></style>