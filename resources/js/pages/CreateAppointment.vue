<template>
  <v-container class="w-100 h-screen main-container">
    <v-card class="mx-auto pt-8 mt-4" elevation="2" max-width="800">
      <h1 class="text-h6 text-md-h5 text-lg-h4 text-center text-bold mb-2">
        Create an Appointment
      </h1>
      <v-stepper :items="['Appointment details', 'Document details']">
        <template v-slot:item.1>
          <v-row>
            <v-col>
              <v-date-picker
                v-model="picker"
                :landscape="landscape"
                :reactive="reactive"
              ></v-date-picker>
            </v-col>
            <v-col>
              <v-text-field
                v-model="firstName"
                label="First name"
                :rules="firstNameRules"
              ></v-text-field>
              <v-text-field
                v-model="firstName"
                label="Last name"
                :rules="firstNameRules"
              ></v-text-field>
              <v-autocomplete
                  :items="states"
                  :custom-filter="customFilter"
                  base-color="white"
                  item-title="name"
                  item-value="abbr"
                  label="State"
                ></v-autocomplete>
            </v-col>
          </v-row>
        </template>

        <template v-slot:item.2> </template>
      </v-stepper>
    </v-card>
  </v-container>
</template>

<script>
export default {
  data: () => ({
    hasSaved: false,
    isEditing: null,
    states: [
      { name: "Florida", abbr: "FL", id: 1 },
      { name: "Georgia", abbr: "GA", id: 2 },
      { name: "Nebraska", abbr: "NE", id: 3 },
      { name: "California", abbr: "CA", id: 4 },
      { name: "New York", abbr: "NY", id: 5 },
    ],
    firstName: "",
    firstNameRules: [
      (value) => {
        if (value?.length > 3) return true;
        return "First name must be at least 3 characters.";
      },
    ],
    lastName: "",
    lastNameRules: [
      (value) => {
        if (/[^0-9]/.test(value)) return true;
        return "Last name can not contain digits.";
      },
    ],
  }),
};
</script>

<style></style>
