<template>
    <v-container class="w-100 h-screen main-container">
        <Navbar></Navbar>
        <div class="container first-container">
            <v-row reverse>
                <v-col cols="12" sm="12" md="6"  class="d-flex align-center justify-content-start">
                    <div class="information-container">
                        <h1 class="mt-10 mb-1">Welcome to Servify</h1>
                        <h2 class="">Barangay San Agustin.</h2>
                        <p class="mb-10">
                            Looking to obtain a certificate from our friendly barangay? Let's make the process easy and hassle-free for you! Simply schedule your appointment and breeze through the paperwork effortlessly. Your convenience is our priority, so let's get started.
                        </p>

                        <v-dialog class="date-appointment">
                            <template v-slot:activator="{ props }">
                                <button class="schedule-btn" v-bind="props">
                                    Get an appointment
                                </button>
                            </template>

                            <template v-slot:default="{ isActive }">
                                <div class="main-modal">
                                    <div class="modal-title">
                                        <div class="title">
                                            <h2>Create Appointment</h2>
                                            <p>
                                                Fill out this form to check if
                                                time is available for your
                                                appointment
                                            </p>
                                        </div>
                                        <button class="close-btn"
                                            @click="isActive.value = false"
                                        ><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                    <p
                                            class="text-danger text--red mb-1 text-center"
                                        >
                                            {{ errorMessage }}
                                        </p>
                                    <div class="main-form">

                                        <div class="mt-2">
                                            <p class="small-label">Certificate type</p>
                                            <v-select
                                                v-model="documentType"
                                                label="Choose Document type*"
                                                :item-props="itemProps"
                                                :items="documentsList"
                                                :rules="[
                                                    (v) =>
                                                        !!v ||
                                                        'Item is required',
                                                ]"
                                            ></v-select>
                                        </div>
                                        <p class="small-label ">Email</p>
                                        <v-text-field
                                            label="Email address*"
                                            placeholder="johndoe@gmail.com"
                                            type="email"
                                            :rules="emailRules"
                                            v-model="email"
                                        ></v-text-field>

                                        <div class="date-time mb-11">
                                            <div class="form-control">
                                                <p class="small-label">Appointment date</p>
                                                <input
                                                    v-model="date"
                                                    class="date-picker custom-input"
                                                    type="date"
                                                    :min="systemDate"
                                                />
                                            </div>
                                            <div class="form-control">
                                                <p class="small-label ">Appointment time</p>
                                                <input
                                                    v-model="time"
                                                    min="08:00"
                                                    max="17:00"
                                                    class="time-picker custom-input"
                                                    type="time"
                                                    @change="validateTime"
                                                />
                                            </div>
                                        </div>
                                        <v-btn
                                            :disabled="loading"
                                            :loading="loading"
                                            block
                                            class="text-none mb-4 primary-btn "
                                            size="x-large"
                                            variant="flat"
                                            append-icon="fa-solid fa-circle-plus"
                                            @click="handleClick"
                                        >
                                            Create an Appointment
                                        </v-btn>
                                    </div>
                                </div>
                            </template>
                        </v-dialog>
                    </div>
                </v-col>
                <v-col cols="12" sm="12" md="6" class="d-flex align-center justify-sm-center justify-md-end">
                    <v-img
                        max-width="500"
                        aspect-ratio="16/9"
                        cover
                        src="images/bookingImg.jpg"
                    ></v-img>
                </v-col>
            </v-row>
        </div>
    </v-container>
</template>

<script>
import Queue from "../pages/Quelist.vue";
import Navbar from "../components/Navbar.vue";
import axios from "axios";
import config from "../utils";
import moment from "moment";
import { ref } from "vue";
import { useAppointmentStore } from "../store/AppointmentStore";
export default {
    components: {
        Navbar,
        Queue,
    },
    setup() {
        const appointmentData = useAppointmentStore;
    },
    data() {
        return {
            show: false,
            loading: false,
            completed: false,
            date: null,
            time: null,
            documentsList: [],
            documentType: null,
            email: null,
            systemDate: null,
            errorMessage: null,
            emailRules: [
                (value) =>
                    /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value) ||
                    "Invalid email address.",
            ],
        };
    },
    methods: {
        validateTime() {
            const selectedTime = new Date(`2000-01-01T${this.time}`);
            const startTime = new Date(`2000-01-01T08:00`);
            const endTime = new Date(`2000-01-01T16:00`);
            const breakTime = new Date(`2000-01-01T12:00`);

            if (selectedTime < startTime || selectedTime > endTime || breakTime === selectedTime) {
                this.errorMessage =
                    "Please add a time between 8:00am to 11:00am and 1:00pm 4:00pm";
                this.completed = false;
            }else {
                this.errorMessage = null;
                this.completed = true;
            }
        },
        itemProps(item) {
            return {
                title: item.name,
            };
        },
        handleClick() {
            if (this.time === null || this.date === null) {
                this.errorMessage =
                    "Please complete all fields to check available date and time";
                return;
            }

            if (!this.completed) {
                this.errorMessage =
                    "Please add a time between 8:00am to 11:00am and 1:00pm 4:00pm";
                return;
            }
            const time = moment(this.time, "HH:mm")
                .add(0, "seconds")
                .format("HH:mm:ss");

            const appointmentData = {
                appointmentTime: time,
                appointmentDate: this.date,
                appointmentEmail: this.email,
                appointmentInputList: this.documentType.input,
                documentId: this.documentType.id,
            };

            this.loading = true;
            const dateTime = `${this.date} ${time}`;

            axios({
                method: "post",
                url: `${config.baseUrl}/checkTimeframe`,
                data: {
                    datetime: dateTime,
                },
            })
                .then((res) => {
                    this.loading = false;
                    if (res.data.is_available) {
                        this.loading = true;
                        setTimeout(() => {
                            useAppointmentStore().saveData(appointmentData);
                            this.$router.push({ name: "Appointment" });
                        }, 1000);
                    } else {
                        this.errorMessage =
                            "No available slots in this date please choose different date";
                    }
                })
                .catch((error) => {
                    console.error("Error occurred:", error);
                });
        },
        getDocuments() {
            axios({
                method: "get",
                url: `${config.baseUrl}/document`,
            })
                .then((res) => {
                    this.documentsList = res.data.documents;
                })
                .catch((error) => {
                    console.error("Error occurred:", error);
                });
        },
    },
    created() {
        this.getDocuments();
        this.systemDate = moment().format("YYYY-MM-DD");
    },
};
</script>

<style lang="scss" scoped>
.first-container {
    margin-top: 15%;
}
.date-time {
    display: flex;
    justify-content: center;
    gap: 10px;
    box-sizing: border-box;
    width: 100%;

    //on small screen flex direction column
    @media (max-width: 768px) {
        flex-direction: column;
    }

    .form-control {
        width: 100%;
    }
}
.text-danger {
    color: red;
    font-size: 0.9rem;
}
.modal-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    position: relative;
    .title {
        h2 {
            margin-bottom: 2px;
            font-size: 28px;

            // on small screen font size is 20px
            @media (max-width: 768px) {
                font-size: 20px;
            }

        }
        p {
            font-size: 14px;
            margin-bottom: 8px;
            font-weight: 500;
        }
    }
}
.schedule-btn {
    background: #ff9f2f;
    padding: 10px 20px;
    border-radius: 4px;
    color: #fff;
    font-weight: 600;
    height: 50px;
    box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
}
.information-container {
    // on small screen text align center
    @media (max-width: 768px) {
        text-align: center;
    }
    h1 {
        font-size: 45px;
        color: #ff9f2f;

        //if small screen font size is 20px
        @media (max-width: 768px) {
            font-size: 30px;
            line-height: 1.1;
        }
    }
}
.main-modal {
    background: #fff;
    padding:20px 30px 30px;
    border-radius: 10px;

    //onsmall screen padding 20px
    @media (max-width: 768px) {
        padding: 20px;
        .main-form {
            padding: 0 !important;
            border: none !important;
        }
    }
}
.close-btn {
    position: absolute;
    right: 0;
    top: 0;
}
.main-form {
    padding: 20px 25px;
    border-radius: 8px;
    border: 1px solid #FFEAD2;
    box-shadow: rgba(255, 251, 195, 0.2) 0px 8px 24px;

}
.main-container {
    background: #fff ;
}

.date-appointment {
    max-width: 600px;

    //on mobile screen width 100%
    @media (max-width: 768px) {
        width: 100%;
        .v-overlay__content {
            width: 100% !important;
            margin: 0 !important;
            max-width: unset !important;
        }
    }
}
.primary-btn {
    //on small screen font size 14px
    @media (max-width: 768px) {
        font-size: 14px;
    }
}


</style>
