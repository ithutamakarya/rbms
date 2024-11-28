import { defineStore } from "pinia";

export const useDataStore = defineStore('data', {
    state: () => ({
        alertMessage: "",
        alertVisibility: false,
        alertType: "success",
        isSidebarDropdownOpen: false,
    }),
    actions: {
        setAlertSuccess(message) {
            this.alertType = 'success'
            this.alertVisibility = true
            this.alertMessage = message
        },
        setAlertError(message) {
            this.alertType = 'error'
            this.alertVisibility = true
            this.alertMessage = message
        },
        toggleSidebarDropdown() {
            this.isSidebarDropdownOpen = !this.isSidebarDropdownOpen
        },
    }
})