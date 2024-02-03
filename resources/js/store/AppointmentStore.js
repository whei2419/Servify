import {defineStore} from "pinia"

export const useAppointmentStore = defineStore('appointmentStore',{
    state: () => ({
        appointmentData:{
            appointmentTime:null,
            appointmentDate:null,
            appointmentEmail:null,
            appointmentInputList:null
        }

    }),
    getters:{
        inputs(){
            return this.appointmentData.inputs
        }
    },
    actions: {
        saveData(data){
            this.appointmentData = data;
        },
    getData(){
        return this.appointmentData;
    }
    },
})
