import { defineStore } from "pinia";

export const useDataStore = defineStore('data', {
    state: () => ({
        alertMessage: "",
        alertVisibility: false,
        alertType: "success",
    }),
    actions: {
        setAlertMessage(message) {
            this.alertMessage = message
        },
        setAlertVisibility(status) {
            this.alertVisibility = status
        },
        setAlertType(type) {
            this.alertType = type
        }
    }
})