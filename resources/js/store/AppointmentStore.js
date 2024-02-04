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
        formatInputs(){
            const inputList = this.appointmentData.appointmentInputList;
            if (!inputList || !Array.isArray(inputList)) {
                return {};
            }

            const groupedInputs = {};
            inputList.forEach(input => {
                const inputType = input.input.type;
                const inputName = input.input.name;
                if (!groupedInputs[inputType]) {
                    groupedInputs[inputType] = [];
                }
                if(inputName !== 'Date'){
                    groupedInputs[inputType].push(input);
                }
            });
            console.log(groupedInputs);

            return groupedInputs;
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
