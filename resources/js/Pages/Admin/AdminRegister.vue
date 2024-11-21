<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import { onMounted, ref, reactive } from "vue";

import { useToast } from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css"; // You can change the theme as needed

import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";
import axios from "axios";

const props = defineProps({
    user: Object,
    settings: Object,
    users: Object,
});

// Use the toast plugin
const toast = useToast();
const showToast = (type = "success", msg) => {
    // Show a toast notification
    toast.open({
        message: msg,
        type: type, // You can use other types like info, error, warning
        duration: 3000, // Auto-hide after 3 seconds
        position: "top-right", // Toast position
    });
};

onMounted(() => {
    console.log(props.users);
});

const errors = ref({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const form = ref({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const register_form = async (event) => {
    event.preventDefault();
    errors.value = {};
    try {
        console.log("submit", form.value);
        const resp = await axios.post(`/api/v1/admin/register`, form.value);
        console.log(resp);
        showToast("success", `${form.value.name} is register successfully`);
    } catch (error) {
        console.log(error);
        showToast("error", `All fields is required!`);
        errors.value.name = error.response.data.errors?.name
            ? error.response.data.errors.name[0]
            : "";
        errors.value.email = error.response.data.errors?.email
            ? error.response.data.errors.email[0]
            : "";
        errors.value.password = error.response.data.errors?.password
            ? error.response.data.errors.password[0]
            : "";
        errors.value.password_confirmation = error.response.data.errors
            ?.password_confirmation
            ? error.response.data.errors.password_confirmation[0]
            : "";
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Settings" />
        <div class="container">
            <div class="row mt-5">
                <div class="col w-50">
                    <form @submit.prevent="register_form">
                        <div class="form-group">
                            <label for="">Full Name</label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                aria-describedby="emailHelp"
                                placeholder="Enter Name"
                                v-model="form.name"
                            />
                            <small
                                id=""
                                class="form-text text-danger"
                                v-if="errors.name"
                                >{{ errors.name }}</small
                            >
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Email address</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                aria-describedby=""
                                placeholder="Enter email"
                                v-model="form.email"
                            />
                            <small
                                id=""
                                class="form-text text-danger"
                                v-if="errors.email"
                                >{{ errors.email }}</small
                            >
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Password</label>
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                placeholder="Password"
                                v-model="form.password"
                            />
                            <small
                                id=""
                                class="form-text text-danger"
                                v-if="errors.password"
                                >{{ errors.password }}</small
                            >
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Confirm Password</label>
                            <input
                                type="password"
                                class="form-control"
                                id="confirm_password"
                                placeholder="Confirm Password"
                                v-model="form.password_confirmation"
                            />
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">
                            Submit
                        </button>
                    </form>
                </div>

                <div class="col-12 mt-5">
                    <table class="table table-hover">
                        <thead>
                            <tr colspan="7">
                                <th class="colspan-6" colspan="7">
                                    <input type="text" class="w-100" placeholder="Search user enter name, email" />
                                </th>
                            </tr>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col" colspan="2" class="text-center">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in users" :key="index">
                                <th scope="row">{{ index + 1 }}</th>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td class="text-center">
                                    <button
                                        class="btn btn-sm btn-primary"
                                        @click="edit_user(user)"
                                    >
                                        Edit
                                    </button>
                                </td>
                                <td class="text-center">
                                    <button
                                        class="btn btn-sm btn-danger"
                                        @click="delete_user(user)"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
